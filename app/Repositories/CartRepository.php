<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Http\Request;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Jobs\SendEmailBuyProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CartRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'product_id',
        'qtys'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Cart::class;
    }

    public function getCart($id)
    {
        $cart = Cart::orderBy('qtys','DESC')->orderBy('id','DESC')->where('user_id', $id)->with('user','product')->get();

        return $cart;
    }

    public function createCart(array $attributes)
    {
        $userID = $attributes['user_id'];
        $productID = $attributes['product_id'];
        $findCart = Cart::where('product_id', $productID)->where('user_id', $userID)->first();

        if(!$findCart){
            $order = Cart::create([
                'product_id' => $attributes['product_id'],
                'user_id' => $attributes['user_id'],
                'qtys' => 1
            ]);
            return $order;
        }else{
            $findCart->update([
                'qtys'=>$findCart['qtys'] + 1,
            ]);
            return $findCart;
        }
    }

    public function checkout(array $attributes, $userId)
    {
        \Stripe\Stripe::setApiKey("sk_test_a0c7xINpzQrxsS5tia1LEwO4");

        try{
            $hasError = false;
            $amount = 0;
            $sumQty = 0;
            $user = \App\Models\User::find($userId);
            if($user && $user->stripe_id){
                $customerId = $user->stripe_id;
            }else{
                // create stripe token
                $token = \Stripe\Token::create(array(
                    "card" => array(
                        "number" => $attributes['card_number'],
                        "exp_month" => $attributes['exp_month'],
                        "exp_year" => $attributes['exp_year'],
                        "cvc" => $attributes['cvc']
                    )
                ));
                // create customer from token
                $customer = \Stripe\Customer::create(array(
                  "description" => 'Customer for '.$user->email,
                  "source" => $token->id
                ));

                // Save stripe_id
                $user->stripe_id    = $customer->id;
                $user->card_brand   = $customer->sources->data[0]->brand;
                $user->card_last_four = $customer->sources->data[0]->last4;
                $user->save();

                $customerId = $customer->id;
            }
            // Create Order
            $order = Order::create([
                'user_id'=>$attributes['user_id'],
                'status'=>false
            ]);

            // get cart of user
            $carts = Cart::where('user_id', $user->id)->get();

            // Create Order Item
            foreach($carts as $cart){

                $order->order_items()->create([
                    'product_id' => $cart['product']['id'],
                    'price' => $cart['product']['price']*$cart['qtys'],
                    'payment_by' => 'stripe'
                ]);

                $sumQty += $cart['qtys'];
                $amount += $cart['product']['price']*$cart['qtys'];
            }


            $sum = ['sumQty'=>$sumQty,'amount'=>$amount];

            //Create Charge
            $charge = \Stripe\Charge::create(array(
                    "amount" => number_format($amount, 2)*100,
                    "currency" => "usd",
                    "customer" => $customerId,
                    "description" => "Phongo Payment"
                )
            );

            if($charge){
                $payment = $order->payment()->create([
                    'token' => '',
                    'brand' => $charge->source->brand,
                    'last4' => $charge->source->last4,
                    'exp_month' => $charge->source->exp_month,
                    'exp_year' => $charge->source->exp_year,
                    'amount' => $amount,
                    'transaction_type' => 1,
                    'transaction_method' => 'Cart',
                    'status' => true
                ]);

                if($payment){
                    // change status order
                    $order->status = true;
                    $order->save();

                    // send mail
                    // $job = new SendEmailBuyProduct($order, $carts, $sum);
                    // dispatch($job);

                    // delete cart
                    $carts = Cart::where('user_id', $user->id)->delete();
                }
            }


        } catch(\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $hasError = true;
            $body = $e->getJsonBody();
            $err  = $body['error'];

        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $hasError = true;
            $body = $e->getJsonBody();
            $err  = $body['error'];
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $hasError = true;
            $body = $e->getJsonBody();
            $err  = $body['error'];
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $hasError = true;
            $body = $e->getJsonBody();
            $err  = $body['error'];
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $hasError = true;
            $body = $e->getJsonBody();
            $err  = $body['error'];
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $hasError = true;
            $body = $e->getJsonBody();
            $err  = $body['error'];
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $hasError = true;
            $body = $e->getJsonBody();
            $err  = $body['error'];
        }

        if(!$hasError){
            return array(
                'error' => false,
                'message' => 'Payment successfully!'
            );
        }else{
            return array(
                'error' => true,
                'message' => $err
            );
        }
    }

    public function updateQty($attributes, $id)
    {
        $qty = $attributes['qty'];

        $findCart = Cart::find($id);

        if ($findCart->qtys != $qty) {
            $findCart->qtys = $qty;
            $findCart->save();
        }

        return $findCart;
    }

    public function getTransactionsByDays(array $attributes)
    {
        $startDay = Carbon::parse($attributes['startDay'])->toDateString();
        $endDay = Carbon::parse($attributes['endDay'])->toDateString();
        $transactions = \App\Models\PaymentTransaction::select([
                        DB::raw('sum(amount) as amount'),
                        DB::raw('DATE(created_at) as day')
                    ])
                    ->whereDate('created_at', '>=', $startDay)
                    ->whereDate('created_at', '<=', $endDay)
                    ->groupBy('day')
                    ->get();

        return $transactions;
    }

    public function getTransactionsByMonths(array $attributes)
    {
        $startMonth = Carbon::parse($attributes['startMonth'])->toDateString();
        $endMonth = Carbon::parse($attributes['endMonth'])->toDateString();
        $transactions = \App\Models\PaymentTransaction::select([
                        DB::raw('sum(amount) as amount'),
                        DB::raw('MONTH(created_at) month'),
                        DB::raw('YEAR(created_at) year'),
                    ])
                    ->whereDate('created_at', '>=', $startMonth)
                    ->whereDate('created_at', '<=', $endMonth)
                    ->groupBy('year', 'month')
                    ->get();

        foreach ($transactions as $key => $value) {
            $transactions[$key]['time'] = Carbon::parse($value->year . '-' . $value->month)->format('Y/m');
        }

        return $transactions;

    }

}
