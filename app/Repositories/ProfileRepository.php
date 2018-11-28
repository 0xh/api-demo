<?php

namespace App\Repositories;

use App\Models\Profile;
use InfyOm\Generator\Common\BaseRepository;

class ProfileRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'address',
        'city_id',
        'phone',
        'postal',
        'country_id',
        'primary',
        'user_id',
        'UUID'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Profile::class;
    }
    public function getProfile($id){

        $profile = Profile::where('user_id',$id)->with('user','country','city')->first();

        if($profile){
            if($profile->user->company){
                $profile->user->company;
                $profile->user->company->country;
                $profile->user->company->city;
            }
        }

        return $profile;
    }


    public function getProfiles(){
        $profiles = Profile::orderBy('id','DESC')->with('country','city')->get();
        $arrProfiles = $profiles->toArray();
        foreach($profiles as $key=>$profile){
            $avatar = $profile->getAvatar();
            $arrProfiles[$key]['url'] = $avatar;
        }
        return $arrProfiles;
    }
    
    public function createCreditCard(array $attributes,$id){
        $user = \App\Models\User::find($id);
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $hasError = false;
        $token = \Stripe\Token::create(array(
              "card" => array(
                "number" => $attributes['card_number'],
                "exp_month" => $attributes['exp_month'],
                "exp_year" => $attributes['exp_year'],
                "cvc" => $attributes['cvc']
              )
            ));

        try{
            $token = \Stripe\Token::create(array(
              "card" => array(
                "number" => $attributes['card_number'],
                "exp_month" => $attributes['exp_month'],
                "exp_year" => $attributes['exp_year'],
                "cvc" => $attributes['cvc']
              )
            ));
            $customer = \Stripe\Customer::create(array(
              "description" => 'Customer for '.$user->id,
              "source" => $token->id
            ));
            $user->stripe_id    = $customer->id;
            $user->card_brand   = $customer->sources->data[0]->brand;
            $user->card_last_four = $customer->sources->data[0]->last4;
            $user->save();

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
            return array(
                'error' => false,
                'data' => $user
            );
        }else{
            return array(
                'error' => true,
                'message' => $err
            );
        }
    }
  
    public function getInforCreditCard($id){
        \Stripe\Stripe::setApiKey(config('services.stripe.key'));
        $infor = \Stripe\Customer::retrieve($id);
        return $infor;
    }

}
