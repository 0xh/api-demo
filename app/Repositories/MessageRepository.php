<?php

namespace App\Repositories;

use App\Models\Message;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\Conversation;
use App\Models\ConversationUsers;
use App\Events\SendMessage;
use App\Events\CreateConversation;
use App\Events\AddMemberToconversation;
use App\Events\RemoveMemberToConversation;
use App\Models\User;
use App\Models\MessageNortification;

class MessageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'body',
        'conversation_id',
        'user_id',
        'read'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Message::class;
    }
    public function checkHasPrivateConversation($attributes){
        $conversations = Conversation::where('author_id',$attributes[0])->orWhere('author_id',$attributes[1])->get();
        foreach ($conversations as $key => $conversation) {
            $users = ConversationUsers::where('conversation_id',$conversation->id)->get();
            $plucked =  $users->pluck('user_id')->toArray();
            if(!empty($plucked)){
                $check = array_diff($plucked, $attributes);
                if(empty($check)){
                    $messages = $conversation->messages();
                    $conversation->users;
                    if($messages){
                        foreach ($conversation->messages as $key => $message) {
                            $user = $message->user;
                            $conversation->messages[$key]['avatar'] = $user->getAvatar();
                        }
                    }
                    return $conversation;
                }
            }
        }
        return ['error'=>true];
    }

    public function createConversation(array $attributes){
        if(count($attributes['userIds'])==2){
            $check = $this->checkHasPrivateConversation($attributes['userIds']);
            if(!$check['error']){
                return $check;
            }
        }

        $conversation = Conversation::create($attributes);
        $conversation->conversation_users;

        if($attributes['userIds']){
            foreach ($attributes['userIds'] as $userId) {
                $conversation->conversation_users()->create([
                    'user_id' => $userId,
                    'conversation_id' => $conversation->id
                ]);
            }
        }

        $conversation->users;
        $arrayUser =[];
        foreach ($conversation->users as $key => $user) {
            if($user->id != $attributes['author_id'])
            array_push($arrayUser, $user->id);
        }
        $conversation['notiMessage'] = 0;
        $conversation['arrayUser'] = $arrayUser;
        $conversation['avatarConver'] = url('/img/default/user-group-chat.png');

        $dataConversation = [
            'id'            => $conversation->id,
            'name'          => $conversation->name,
            'author_id'     => $conversation->author_id,
            'notiMessage'   => 0,
            'arrayUser'     => $arrayUser,
            'avatarConver'  => url('/img/default/user-group-chat.png'),
            'users'         => $conversation->users
        ];

        event(new CreateConversation($dataConversation));
        return $conversation;
    }
    public function getMessages($id){
        $messages = Message::orderBy('id','DESC')->where('conversation_id',$id)->limit(20)->get();

        foreach ($messages as $key => $message) {
            $user = $message->user;
            $user->profile;
            $messages[$key]['avatar'] = $user->getAvatar();
        }
        return $messages;
    }
    public function sendMessage(array $attributes){

        $message = Message::create($attributes);
        $user = User::find($message->user_id);
        $avatar = $user->getAvatar();
        $dataMesage = [
            'body'=>$message->body,
            'conversation_id'=>$message->conversation_id,
            'user_id'=>$message->user_id,
            'read'=>false,
            'room'=>$attributes['idUsers'],
            'avatar'=> $avatar
        ];
        // dd($attributes['idUsers']);
        if($attributes['idUsers']){
            foreach ($attributes['idUsers'] as $id) {
                $message->messages_notifications()->create([
                    'user_id' => $id,
                    'message_id'=>$message->id,
                    'conversation_id'=>$message->conversation_id,
                    ]);
            }
        }
        event(new SendMessage($dataMesage));

        return $message;
    }
    public function getConversations($userId){

        $user = User::find($userId);

        if(!empty($user)){
            $conversations = $user->conversations;

            foreach ($conversations as $key => $conversation) {
                $conversations[$key]['msgUnRead'] = $conversation->countMessageNotRead($userId);

                $countUsersInConvertsation = $conversation->users->count();

                if($countUsersInConvertsation == 2){
                    $conversations[$key]['private'] = true;

                    foreach ($conversation->users as $user) {
                        if($user->id != $userId){
                            if($user->profile){
                                $name = $user->profile->name;
                            }else{
                                $name = $user->email;
                            }

                            $conversations[$key]['nameFriend'] = $name;
                            $conversations[$key]['avatarConver'] = $user->getAvatar();
                            $conversations[$key]['id_private'] = $user->id;
                        }
                    }
                }else{
                    $conversations[$key]['private'] = false;
                    $conversations[$key]['avatarConver'] = url('/img/default/user-group-chat.png');
                }
            }

            return $conversations;
        }else{
            return null;
        }
    }
    public function addMemberToConversation(array $attributes, $id){
        foreach ($attributes['userIds'] as  $userID) {
            $conversation_user = ConversationUsers::create([
                'user_id'=>$userID,
                'conversation_id'=>$id
                ]);
        };
        $conversation = Conversation::find($id);
        $conversation['private'] = false;
        $conversation['avatarConver'] = url('/img/default/user-group-chat.png');
        $conversation['msgUnRead'] = 0;
        $conversation->users;
        $arrayUser = $conversation->users->pluck('id');
        $dataConversation = [
            'id'            => $conversation->id,
            'name'          => $conversation->name,
            'author_id'     => $conversation->author_id,
            'msgUnRead'   => 0,
            'arrayUser'     => $arrayUser->toArray(),
            'avatarConver'  => url('/img/default/user-group-chat.png'),
            'users'         => $conversation->users
        ];
        event(new AddMemberToconversation($dataConversation));
        return $conversation;
    }
    public function removeMemberToConversation(array $attributes){
        $conversation_user = ConversationUsers::where('user_id',$attributes['userId'])->where('conversation_id',$attributes['conversation_id'])->delete();
        $conversation = Conversation::find($attributes['conversation_id']);
        $conversation['private'] = false;
        $conversation['avatarConver'] = url('/img/default/user-group-chat.png');
        $conversation['msgUnRead'] = 0;
        $conversation->users;
        $arrayUser = $conversation->users->pluck('id')->toArray();
        array_push($arrayUser,$attributes['userId']);
        $dataConversation = [
            'id'            => $conversation->id,
            'name'          => $conversation->name,
            'author_id'     => $conversation->author_id,
            'msgUnRead'   => 0,
            'arrayUser'     => $arrayUser,
            'avatarConver'  => url('/img/default/user-group-chat.png'),
            'users'         => $conversation->users
        ];
        event(new RemoveMemberToConversation($dataConversation));
        return $conversation;
    }
    public function getMoreMessages ($attributes){
        $messages = Message::orderBy('id','DESC')->where('conversation_id',$attributes['idConversation'])->where('id','<',$attributes['idLastMessage'])->limit(10)->get();

            foreach ($messages as $key => $message) {
                $user = $message->user;
                $user->profile;
                $messages[$key]['avatar'] = $user->getAvatar();
            }
        return $messages;
    }
}
