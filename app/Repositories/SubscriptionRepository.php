<?php

namespace App\Repositories;

use App\Models\Subscription;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubscriptionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'UUID',
        'plan_id',
        'name',
        'stripe_id',
        'stripe_plan',
        'quantity',
        'trial_ends_at',
        'ends_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Subscription::class;
    }

    /**
     * Admin create a subscription
     */
    public function createSubscriptionStripe($userId, $planId, $token = null)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = User::find($userId);

        if(!is_null($user))
        {

            if (!$this->isStripeCustomer($user))
            {
                $customer = $this->createStripeCustomer($user, $token);
            }
            else
            {
                $customer = \Stripe\Customer::retrieve($user->stripe_id);
            }

            $subscription = \Stripe\Subscription::create(array(
              "customer" => $customer->id,
              "plan" => $planId
            ));

            return $subscription;

        }

    }


    /**
     * Subscribe a plan by users
     */
    public function subscribePlan(array $attribute, $token = null)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $token = \Stripe\Token::create(array(
          "card" => array(
            "number" => "4242424242424242",
            "exp_month" => 4,
            "exp_year" => 2018,
            "cvc" => "666"
          )
        ));


        $user = \Auth::user();

        $user->newSubscription($attribute['name'], $attribute['plan_id'])->create($token->id, [
            'email' => $user->email
        ]);
    }

    /**
     * Check if the Stripe customer exists.
     *
     * @return boolean
     */
    public function isStripeCustomer(User $user)
    {
        return $user->where('id', $user->id)->whereNotNull('stripe_id')->first();
    }

    /**
     * Create a new Stripe customer for a given user.
     *
     * @var    Stripe\Customer $customer
     * @param  string          $token
     * @return Stripe\Customer $customer
     */
    public function createStripeCustomer(User $user, $token)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $customer = \Stripe\Customer::create(array(
            "description" => $user->email,
            "source" => $token
        ));

        $user->stripe_id = $customer->id;
        $user->save();

        return $customer;
    }

    /**
     * Get all subscriptions
     *
     * @param  array $columns
     * @return App\Models\Subscription $subscription
     */
    public function all($columns = ['*']){
        $subscriptions = Subscription::orderBy('id', 'DESC')->with(array('user' => function($query){
                $query->select('id', 'email');
            }))->with(array('plan' => function($query){
                $query->select('id', 'plan_id', 'name', 'amount', 'currency', 'interval');
            }))->get();

        return $subscriptions;
    }


    public function getAllInvoices(array $filters){
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $hasError = false;
        try{
            $invoices = \Stripe\Invoice::all($filters);
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
            $err  = $body['error'];;
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
            return $invoices;
        }else{
            return array(
                'error' => true,
                'message' => $err
            );
        }

    }

    public function getAllPayments(array $filters){
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $hasError = false;
        try{
            $payments = \Stripe\Charge::all($filters);
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
            $err  = $body['error'];;
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
            return $payments;
        }else{
            return array(
                'error' => true,
                'message' => $err
            );
        }

    }

    public function retrieveInvoice($invoiceId){
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $hasError = false;
        try{
            $invoice = \Stripe\Invoice::retrieve($invoiceId);
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
            $err  = $body['error'];;
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
            return $invoice;
        }else{
            return array(
                'error' => true,
                'message' => $err
            );
        }
    }

    public function retrievePayment($paymentId){
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $hasError = false;
        try{
            $payment = \Stripe\Charge::retrieve($paymentId);
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
            $err  = $body['error'];;
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
            return $payment;
        }else{
            return array(
                'error' => true,
                'message' => $err
            );
        }
    }

    public function retrieveBalanceTransaction($balanceTransactionId){
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $hasError = false;
        try{
            $balanceTransaction = \Stripe\BalanceTransaction::retrieve($balanceTransactionId);
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
            $err  = $body['error'];;
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
            return $balanceTransaction;
        }else{
            return array(
                'error' => true,
                'message' => $err
            );
        }
    }

    public function getAllEvents($filters){
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $hasError = false;
        try{
            $events = \Stripe\Event::all($filters);
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
            $err  = $body['error'];;
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
            return $events;
        }else{
            return array(
                'error' => true,
                'message' => $err
            );
        }
    }

    public function getSubscription(array $attribute){

        if($attribute['start_month'] && $attribute['end_month']){
            // $start_month = $attribute['start_month'];
            // $end_month = $attribute['end_month'];
            $start_month = $attribute['start_month'].'-01';
            $end_month = $attribute['end_month'].'-31';
            // dd($start_month);
            $subscriptions = Subscription::orderBy('id', 'DESC')->whereBetween(DB::raw('date(created_at)'),[$start_month,$end_month])->with(array('user' => function($query){
                $query->select('id', 'email');

            }))->with(array('plan' => function($query){
                $query->select('id', 'plan_id', 'name', 'amount', 'currency', 'interval');
            }))->get();


            // $subscriptions = Subscription::orderBy('id','DESC')->whereMonth('created_at','=',5)->whereYear('created_at','=',2017)->get();

        }elseif($attribute['start_day'] && $attribute['end_day']){


            $start_day = $attribute['start_day'];
            $end_day = $attribute['end_day'];

            $subscriptions = Subscription::orderBy('id', 'DESC')->whereBetween(DB::raw('date(created_at)'),[$start_day,$end_day])->with(array('user' => function($query){
                $query->select('id', 'email');
            }))->with(array('plan' => function($query){
                $query->select('id', 'plan_id', 'name', 'amount', 'currency', 'interval');
            }))->get();
        }else{
            $subscriptions = null;
        }
        return $subscriptions;
    }

    public function getSubscriptionByDays(array $attribute){
        $startDay = Carbon::parse($attribute['startDay'])->toDateString();
        $endDay = Carbon::parse($attribute['endDay'])->toDateString();
        $subscriptions = Subscription::select([
                        DB::raw('COUNT(id) as count'),
                        DB::raw('DATE(created_at) as day')
                    ])
                    ->whereDate('created_at', '>=', $startDay)
                    ->whereDate('created_at', '<=', $endDay)
                    ->groupBy('day')
                    ->get();

        return $subscriptions;
    }

    public function getSubscriptionByMonths(array $attribute){
        $startMonth = Carbon::parse($attribute['startMonth'])->toDateString();
        $endMonth = Carbon::parse($attribute['endMonth'])->toDateString();
        $subscriptions = Subscription::select([
                        DB::raw('COUNT(id) as count'),
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('YEAR(created_at) as year')
                    ])
                    ->whereDate('created_at', '>=', $startMonth)
                    ->whereDate('created_at', '<=', $endMonth)
                    ->groupBy('year', 'month')
                    ->get();

        foreach ($subscriptions as $key => $value) {
            $subscriptions[$key]['time'] = Carbon::parse($value->year . '-' . $value->month)->format('Y/m');
        }

        return $subscriptions;
    }
}
