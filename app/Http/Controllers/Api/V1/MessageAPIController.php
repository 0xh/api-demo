<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateMessageAPIRequest;
use App\Http\Requests\API\UpdateMessageAPIRequest;
use App\Http\Requests\API\CreateConversationAPIRequest;
use App\Models\Message;
use App\Repositories\MessageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\MessageNortification;
use App\Models\Conversation;
use App\Models\ConversationUsers;
use App\Models\User;

/**
 * Class MessageController
 * @package App\Http\Controllers\Api\V1
 */

class MessageAPIController extends AppBaseController
{
    /** @var  MessageRepository */
    private $messageRepository;

    public function __construct(MessageRepository $messageRepo)
    {
        $this->messageRepository = $messageRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/messages",
     *      summary="Get a listing of the Messages.",
     *      tags={"Message"},
     *      description="Get all Messages",
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
     *                  @SWG\Items(ref="#/definitions/Message")
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
        $this->messageRepository->pushCriteria(new RequestCriteria($request));
        $this->messageRepository->pushCriteria(new LimitOffsetCriteria($request));
        $messages = $this->messageRepository->all();

        return $this->sendResponse($messages->toArray(), 'Messages retrieved successfully');
    }

    /**
     * @param CreateMessageAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/messages",
     *      summary="Store a newly created Message in storage",
     *      tags={"Message"},
     *      description="Store Message",
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
     *          description="Message that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Message")
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
     *                  ref="#/definitions/Message"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateMessageAPIRequest $request)
    {
        $input = $request->all();

        $messages = $this->messageRepository->create($input);

        return $this->sendResponse($messages->toArray(), 'Message saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/messages/{id}",
     *      summary="Display the specified Message",
     *      tags={"Message"},
     *      description="Get Message",
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
     *          description="id of Message",
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
     *                  ref="#/definitions/Message"
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
        /** @var Message $message */
        $message = $this->messageRepository->findWithoutFail($id);

        if (empty($message)) {
            return $this->sendError('Message not found');
        }

        return $this->sendResponse($message->toArray(), 'Message retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateMessageAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/messages/{id}",
     *      summary="Update the specified Message in storage",
     *      tags={"Message"},
     *      description="Update Message",
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
     *          description="id of Message",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Message that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Message")
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
     *                  ref="#/definitions/Message"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateMessageAPIRequest $request)
    {
        $input = $request->all();

        /** @var Message $message */
        $message = $this->messageRepository->findWithoutFail($id);

        if (empty($message)) {
            return $this->sendError('Message not found');
        }

        $message = $this->messageRepository->update($input, $id);

        return $this->sendResponse($message->toArray(), 'Message updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/messages/{id}",
     *      summary="Remove the specified Message from storage",
     *      tags={"Message"},
     *      description="Delete Message",
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
     *          description="id of Message",
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
        /** @var Message $message */
        $message = $this->messageRepository->findWithoutFail($id);

        if (empty($message)) {
            return $this->sendError('Message not found');
        }

        $message->delete();

        return $this->sendResponse($id, 'Message deleted successfully');
    }

    public function createConversation(CreateConversationAPIRequest $request){
        $input = $request->all();

        $conversation = $this->messageRepository->createConversation($input);

        return $this->sendResponse($conversation->toArray(), 'Conversation saved successfully');
    }

    public function getMessages($id){
        $message = $this->messageRepository->getMessages($id);

        if (empty($message)) {
            return $this->sendError('Message not found');
        }

        return $this->sendResponse($message->toArray(), 'Message retrieved successfully');
    }

    public function sendMessage(Request $request){
        $input = $request->all();

        $messages = $this->messageRepository->sendMessage($input);

        return $this->sendResponse($messages->toArray(), 'Message saved successfully');
    }

    public function getConversations($id){
        $conversations = $this->messageRepository->getConversations($id);

        if (empty($conversations)) {
            return $this->sendError('Conversations not found');
        }

        return $this->sendResponse($conversations->toArray(), 'Conversations retrieved successfully');

    }

    public function checktReadMessage(Request $request){
        $input = $request->all();
        $nuti = MessageNortification::where('conversation_id',$input['conversation_id'])->where('user_id',$input['user_id'])->update(['read'=>true]);
        return $nuti;
    }
    public function editNameConversation(Request $request){
        $input = $request->all();
        $conversation = Conversation::find($input['id'])->update([
            'name'=>$input['name']
            ]);
        return $this->sendResponse($input, 'Conversation update successfully');
        
    }
    public function deleteConversation($id){
        $conversation = Conversation::find($id);
        $conversation_user = ConversationUsers::where('conversation_id',$id)->delete();
        $messages = Message::where('conversation_id',$id)->delete();
        $conversation->delete();
        return $conversation;
    }
    public function checkConversation(Request $request){
        $input = $request->all();
        $conversation = $this->messageRepository->checkHasPrivateConversation($input);
        if($conversation['error']){
            return $this->sendResponseFalse([], 'Conversation not found !!');
        }else{
            return $this->sendResponse($conversation, 'Conversation retrieved successfully');
        }
    }
    public function addMemberToConversation(Request $request,$id){
        $input = $request->all();
        $conversation = $this->messageRepository->addMemberToConversation($input,$id);
        return $this->sendResponse($conversation->toArray(), 'Conversation update successfully');
    }
    public function removeMemberToConversation(Request $request){
        $input = $request->all();
        $conversation = $this->messageRepository->removeMemberToConversation($input);
        return $this->sendResponse($conversation->toArray(), 'Conversation update successfully');
    }
    public function getMoreMessages(Request $request){
        $input = $request->all();
        $message = $this->messageRepository->getMoreMessages($input);

        if (empty($message)) {
            return $this->sendError('Message not found');
        }

        return $this->sendResponse($message->toArray(), 'Message retrieved successfully');
    }
}
