<?php

namespace App\Repositories;

use App\Models\Plan;
use InfyOm\Generator\Common\BaseRepository;

class PlanRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'description',
        'amount',
        'currency',
        'interval'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Plan::class;
    }

    public function listAllStripePlan(){
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $planStripe = \Stripe\Plan::all();

        return $planStripe;
    }

    public function createStripePlan(array $plan)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $hasError = false;
        $plan['amount'] = $this->removeDot($plan['amount']);
        try {
            $planStripe = \Stripe\Plan::create(array(
              "amount" => $plan['amount'],
              "interval" => $plan['interval'],
              "interval_count" => $plan['interval_count'],
              "name" => $plan['name'],
              "currency" => $plan['currency'],
              "id" => $plan['plan_id'])
            );
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
            return $planStripe;
        }else{
            return array(
                'error' => true,
                'message' => $err
            );
        }

    }

    public function retrieveStripePlan($id)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $planStripe = \Stripe\Plan::retrieve($id);

        return $planStripe;
    }

    public function updateStripePlan(array $plan)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $hasError = false;
        try {
            $planStripe = \Stripe\Plan::retrieve($plan['plan_id']);
            // Stripe allow update only name plan
            $planStripe->name = $plan['name'];
            $planStripe->save();
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
            return $planStripe;
        }else{
            return array(
                'error' => true,
                'message' => $err
            );
        }
    }

    public function deleteStripePlan($plan_id)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $hasError = false;
        try {
            $planStripe = \Stripe\Plan::retrieve($plan_id);
            $planStripe->delete();
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
            return $planStripe;
        }else{
            return array(
                'error' => true,
                'message' => $err
            );
        }
    }

    public function removeDot($amount){
        if(str_contains($amount, ".")){
            return str_replace(".", "", $amount);
        }else{
            return $amount;
        }
    }

}
