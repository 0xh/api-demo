<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateCartAPIRequest;
use App\Http\Requests\API\UpdateCartAPIRequest;
use App\Models\Cart;
use App\Models\PaymentTransaction;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class CartController
 * @package App\Http\Controllers\Api\V1
 */

class CartAPIController extends AppBaseController
{
    /** @var  CartRepository */
    private $cartRepository;

    public function __construct(CartRepository $cartRepo)
    {
        $this->cartRepository = $cartRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/carts",
     *      summary="Get a listing of the Carts.",
     *      tags={"Cart"},
     *      description="Get all Carts",
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
     *                  @SWG\Items(ref="#/definitions/Cart")
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
        $this->cartRepository->pushCriteria(new RequestCriteria($request));
        $this->cartRepository->pushCriteria(new LimitOffsetCriteria($request));
        $carts = $this->cartRepository->all();

        return $this->sendResponse($carts->toArray(), 'Carts retrieved successfully');
    }

    /**
     * @param CreateCartAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/carts",
     *      summary="Store a newly created Cart in storage",
     *      tags={"Cart"},
     *      description="Store Cart",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Cart that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Cart")
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
     *                  ref="#/definitions/Cart"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateCartAPIRequest $request)
    {
        $input = $request->all();

        $cart = $this->cartRepository->createCart($input);

        return $this->sendResponse($cart->toArray(), 'Cart saved successfully');

    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/carts/{id}",
     *      summary="Display the specified Cart",
     *      tags={"Cart"},
     *      description="Get Cart",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Cart",
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
     *                  ref="#/definitions/Cart"
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
        /** @var Cart $cart */
        $cart = $this->cartRepository->findWithoutFail($id);

        if (empty($cart)) {
            return $this->sendError('Cart not found');
        }

        return $this->sendResponse($cart->toArray(), 'Cart retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateCartAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/carts/{id}",
     *      summary="Update the specified Cart in storage",
     *      tags={"Cart"},
     *      description="Update Cart",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Cart",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Cart that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Cart")
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
     *                  ref="#/definitions/Cart"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, Request $request)
    {
        $input = $request->all();

        /** @var Cart $cart */
        $cart = $this->cartRepository->findWithoutFail($id);
        if (empty($cart)) {
            return $this->sendError('Cart not found');
        }
        $cart = $this->cartRepository->updateQty($input, $id);

        return $this->sendResponse($cart->toArray(), 'Cart updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/carts/{id}",
     *      summary="Remove the specified Cart from storage",
     *      tags={"Cart"},
     *      description="Delete Cart",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Cart",
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
        /** @var Cart $cart */
        $cart = $this->cartRepository->findWithoutFail($id);

        if (empty($cart)) {
            return $this->sendError('Cart not found');
        }

        $cart->delete();

        return $this->sendResponse($id, 'Cart deleted successfully');
    }

    public function getCart(Request $request,$userID)
    {
        $carts = $this->cartRepository->getCart($userID);

        return $this->sendResponse($carts->toArray(), 'Carts retrieved successfully');
    }

    public function checkout(Request $request,$userID)
    {
        $input = $request->all();

        $payment = $this->cartRepository->checkout($input,$userID);

        if ($payment['error']) {
            return $this->sendError($payment['message']);
        }

        return $this->sendResponse($payment, 'Payment successfully');
    }

    public function getPaymentTransaction(Request $request)
    {
        $input = $request->all();

        if($request->input('startDay') && $request->input('endDay')){
            $transactions = $this->cartRepository->getTransactionsByDays($input);
        }
        elseif($request->input('startMonth') && $request->input('endMonth')){
            $transactions = $this->cartRepository->getTransactionsByMonths($input);
        }
        else{
             return $this->sendError('Parameter is wrong');
        }

        if(empty($transactions)){
            return $this->sendError('Transactions not found');
        }

        return $this->sendResponse($transactions, 'Transactions retrieved successfully');

    }
}
