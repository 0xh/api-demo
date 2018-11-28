<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateSubscriptionAPIRequest;
use App\Http\Requests\API\UpdateSubscriptionAPIRequest;
use App\Models\Subscription;
use App\Repositories\SubscriptionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class SubscriptionController
 * @package App\Http\Controllers\Api\V1
 */

class SubscriptionAPIController extends AppBaseController
{
    /** @var  SubscriptionRepository */
    private $subscriptionRepository;

    public function __construct(SubscriptionRepository $subscriptionRepo)
    {
        $this->subscriptionRepository = $subscriptionRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/subscriptions",
     *      summary="Get a listing of the Subscriptions.",
     *      tags={"Subscription"},
     *      description="Get all Subscriptions",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Subscription")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $this->subscriptionRepository->pushCriteria(new RequestCriteria($request));
        $this->subscriptionRepository->pushCriteria(new LimitOffsetCriteria($request));
        $subscriptions = $this->subscriptionRepository->all();

        return $this->sendResponse($subscriptions->toArray(), 'Subscriptions retrieved successfully');
    }

    /**
     * @param CreateSubscriptionAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/subscriptions",
     *      summary="Store a newly created Subscription in storage",
     *      tags={"Subscription"},
     *      description="Store Subscription",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Subscription that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Subscription")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Subscription"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateSubscriptionAPIRequest $request)
    {
        $input = $request->all();

        $subscriptions = $this->subscriptionRepository->create($input);

        return $this->sendResponse($subscriptions->toArray(), 'Subscription saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/subscriptions/{id}",
     *      summary="Display the specified Subscription",
     *      tags={"Subscription"},
     *      description="Get Subscription",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Subscription",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Subscription"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Subscription $subscription */
        $subscription = $this->subscriptionRepository->findWithoutFail($id);

        if (empty($subscription)) {
            return $this->sendError('Subscription not found');
        }

        return $this->sendResponse($subscription->toArray(), 'Subscription retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateSubscriptionAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/subscriptions/{id}",
     *      summary="Update the specified Subscription in storage",
     *      tags={"Subscription"},
     *      description="Update Subscription",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Subscription",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Subscription that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Subscription")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Subscription"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateSubscriptionAPIRequest $request)
    {
        $input = $request->all();

        /** @var Subscription $subscription */
        $subscription = $this->subscriptionRepository->findWithoutFail($id);

        if (empty($subscription)) {
            return $this->sendError('Subscription not found');
        }

        $subscription = $this->subscriptionRepository->update($input, $id);

        return $this->sendResponse($subscription->toArray(), 'Subscription updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/subscriptions/{id}",
     *      summary="Remove the specified Subscription from storage",
     *      tags={"Subscription"},
     *      description="Delete Subscription",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Subscription",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Subscription $subscription */
        $subscription = $this->subscriptionRepository->findWithoutFail($id);

        if (empty($subscription)) {
            return $this->sendError('Subscription not found');
        }

        $subscription->delete();

        return $this->sendResponse($id, 'Subscription deleted successfully');
    }


    public function subscribePlan(Request $request)
    {
        $input = $request->all();

        // Validate here

        $this->subscriptionRepository->subscribePlan($input);

    }

    public function getInvoices(Request $request){
        $filters = $request->all();

        $invoices = $this->subscriptionRepository->getAllInvoices($filters);

        if(!is_null($invoices) && $invoices['error']){
            return $this->sendError($invoices['message']);
        }

        return $this->sendResponse($invoices, ' Invoices retrieved successfully');
    }

    public function retrieveInvoice($invoiceId){
        $invoice = $this->subscriptionRepository->retrieveInvoice($invoiceId);

        if(!is_null($invoice) && $invoice['error']){
            return $this->sendError($invoice['message']);
        }

        return $this->sendResponse($invoice, ' Invoices retrieved successfully');
    }

    public function getPayments(Request $request){
        $filters = $request->all();

        $payments = $this->subscriptionRepository->getAllPayments($filters);

        if(!is_null($payments) && $payments['error']){
            return $this->sendError($payments['message']);
        }

        return $this->sendResponse($payments, ' Payments retrieved successfully');
    }


    public function retrievePayment($paymentId){
        $payment = $this->subscriptionRepository->retrievePayment($paymentId);

        if(!is_null($payment) && $payment['error']){
            return $this->sendError($payment['message']);
        }

        return $this->sendResponse($payment, ' Payment retrieved successfully');
    }

    public function retrieveBalanceTransaction($balanceTransactionId){
        $balanceTransaction = $this->subscriptionRepository->retrieveBalanceTransaction($balanceTransactionId);

        if(!is_null($balanceTransaction) && $balanceTransaction['error']){
            return $this->sendError($balanceTransaction['message']);
        }

        return $this->sendResponse($balanceTransaction, ' Balance Transaction retrieved successfully');
    }

    public function getEvents(Request $request){
        $filters = $request->all();

        $events = $this->subscriptionRepository->getAllEvents($filters);

        if(!is_null($events) && $events['error']){
            return $this->sendError($events['message']);
        }

        return $this->sendResponse($events, ' Events retrieved successfully');
    }
    public function getSubscribePlan(Request $request){
        $input = $request->all();

        if($request->input('startDay') && $request->input('endDay')){
            $subscription = $this->subscriptionRepository->getSubscriptionByDays($input);
        }
        elseif($request->input('startMonth') && $request->input('endMonth')){
            $subscription = $this->subscriptionRepository->getSubscriptionByMonths($input);
        }
        else{
             return $this->sendError('Parameter is wrong');
        }

        if(empty($subscription)){
            return $this->sendError('Subscription not found');
        }

        return $this->sendResponse($subscription, 'Subscription retrieved successfully');

    }

    public function getCountSubscription(Request $request){
        $input = $request->all();

        if($request->input('startDay') && $request->input('endDay')){
            $subscription = $this->subscriptionRepository->getSubscriptionByDays($input);
        }
        elseif($request->input('startMonth') && $request->input('endMonth')){
            $subscription = $this->subscriptionRepository->getSubscriptionByMonths($input);
        }
        else{
             return $this->sendError('Parameter is wrong');
        }

        if(empty($subscription)){
            return $this->sendError('Subscription not found');
        }

        return $this->sendResponse($subscription, 'Subscription retrieved successfully');

    }
}
