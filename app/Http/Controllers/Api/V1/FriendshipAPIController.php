<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateFriendshipAPIRequest;
use App\Http\Requests\API\UpdateFriendshipAPIRequest;
use App\Models\Friendship;
use App\Repositories\FriendshipRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\User;

/**
 * Class FriendshipController
 * @package App\Http\Controllers\Api\V1
 */

class FriendshipAPIController extends AppBaseController
{
    /** @var  FriendshipRepository */
    private $friendshipRepository;

    public function __construct(FriendshipRepository $friendshipRepo)
    {
        $this->friendshipRepository = $friendshipRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/friendships",
     *      summary="Get a listing of the Friendships.",
     *      tags={"Friendship"},
     *      description="Get all Friendships",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          type="string",
     *          description="Authorization",
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
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Friendship")
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
        $this->friendshipRepository->pushCriteria(new RequestCriteria($request));
        $this->friendshipRepository->pushCriteria(new LimitOffsetCriteria($request));
        $friendships = $this->friendshipRepository->all();

        return $this->sendResponse($friendships->toArray(), 'Friendships retrieved successfully');
    }

    /**
     * @param CreateFriendshipAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/friendships",
     *      summary="Store a newly created Friendship in storage",
     *      tags={"Friendship"},
     *      description="Store Friendship",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          type="string",
     *          description="Authorization",
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Friendship that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Friendship")
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
     *                  ref="#/definitions/Friendship"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateFriendshipAPIRequest $request)
    {
        $input = $request->all();

        $friendships = $this->friendshipRepository->create($input);

        return $this->sendResponse($friendships->toArray(), 'Friendship saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/friendships/{id}",
     *      summary="Display the specified Friendship",
     *      tags={"Friendship"},
     *      description="Get Friendship",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          type="string",
     *          description="Authorization",
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Friendship",
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
     *                  ref="#/definitions/Friendship"
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
        /** @var Friendship $friendship */
        $friendship = $this->friendshipRepository->findWithoutFail($id);

        if (empty($friendship)) {
            return $this->sendError('Friendship not found');
        }

        return $this->sendResponse($friendship->toArray(), 'Friendship retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFriendshipAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/friendships/{id}",
     *      summary="Update the specified Friendship in storage",
     *      tags={"Friendship"},
     *      description="Update Friendship",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          type="string",
     *          description="Authorization",
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Friendship",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Friendship that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Friendship")
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
     *                  ref="#/definitions/Friendship"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateFriendshipAPIRequest $request)
    {
        $input = $request->all();

        /** @var Friendship $friendship */
        $friendship = $this->friendshipRepository->findWithoutFail($id);

        if (empty($friendship)) {
            return $this->sendError('Friendship not found');
        }

        $friendship = $this->friendshipRepository->update($input, $id);

        return $this->sendResponse($friendship->toArray(), 'Friendship updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/friendships/{id}",
     *      summary="Remove the specified Friendship from storage",
     *      tags={"Friendship"},
     *      description="Delete Friendship",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          type="string",
     *          description="Authorization",
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Friendship",
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
        /** @var Friendship $friendship */
        $friendship = $this->friendshipRepository->findWithoutFail($id);

        if (empty($friendship)) {
            return $this->sendError('Friendship not found');
        }

        $friendship->delete();

        return $this->sendResponse($id, 'Friendship deleted successfully');
    }
    public function invitation($email){
        \Mail::send('invitation',['email'=>$email], function ($masg) use ($email) {
            $masg->to($email)
                ->subject('SYSTEM');
        });
    }

    public function addFriend(Request $request){
        $input = $request->all();
        $receiver = User::where('email', $input['receiver_email'])->where('id', '<>', $input['sender_id'])->first();

        if(!$receiver){
            $this->invitation($input['receiver_email']);
            /// send a invitation
            return $this->sendResponseWithCode([], 100);
        }
        $check = Friendship::where([
                                    'receiver_id'=>$input['sender_id'],
                                    'sender_id'=>$receiver->id
                                ])
                            ->orWhere(function($query) use ( $receiver,$input ){
                                $query->where('receiver_id',$receiver->id)->where('sender_id',$input['sender_id']);
                            })
                            ->first();
        if($check){
            return $this->sendError('You sent the request to this email or friendship already exists');
        }

        $friendship = $this->friendshipRepository->addFriend($input);

        return $this->sendResponse($friendship->toArray(), 'Friendship saved successfully');

    }

    public function confirmAddFriend(Request $request){

        $input = $request->all();

        $friendships = $this->friendshipRepository->confirmAddFriend($input);

        return $friendships;

    }

    public function getListFriend($id){
        $friendships = $this->friendshipRepository->getListFriend($id);

        return $this->sendResponse($friendships->toArray(), 'Friendships retrieved successfully');
    }
    public function getListEmployees($id){
        $members = $this->friendshipRepository->getListEmployees($id);

        return $this->sendResponse($members->toArray(), 'Members retrieved successfully');
    }
    public function getListMembers($id){
        $owner = $this->friendshipRepository->getListMembers($id);

        return $this->sendResponse($owner->toArray(), 'Members retrieved successfully');
    }
    public function getOwner($id){
        $owner = $this->friendshipRepository->getOwner($id);

        return $this->sendResponse($owner->toArray(), 'Members retrieved successfully');
    }

    public function unFriend(Request $request){
        $input = $request->all();

        $unFriend = $this->friendshipRepository->unFriend($input);

        return $unFriend;
    }

    public function searchEmailFriend( Request $request){
        $input = $request->all();

        $searchEmailFriend = $this->friendshipRepository->searchEmailFriend($input);

        return $this->sendResponse($searchEmailFriend, 'Friends retrieved successfully');
    }
}
