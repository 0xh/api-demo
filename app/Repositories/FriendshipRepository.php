<?php

namespace App\Repositories;

use App\Models\Friendship;
use InfyOm\Generator\Common\BaseRepository;
use App\Models\User;
use App\Models\Company;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\AcceptFriend;
use App\Events\AcceptFriendEvent;
use App\Events\AcceptTrueFriend;
use App\Events\UnfriendEvent;
use Illuminate\Support\Facades\DB;
class FriendshipRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'sender_id',
        'receiver_id',
        'status'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Friendship::class;
    }
    public function addFriend(array $attribute){
        $receiver = User::where('email',$attribute['receiver_email'])->first();

        $friendship = Friendship::create([
            'sender_id'=>$attribute['sender_id'],
            'receiver_id'=>$receiver->id,
            'status'=> 0
        ]);

        $sender = User::find($attribute['sender_id']);

        $notification = Notification::create([
            'content'=> $sender->email.' has sent a friend request to you',
            'status'=>false,
            'sender'=>$attribute['sender_id'],
            'receiver'=> $receiver->id,
            'type' => 'friend',
            'friendship_id' => $friendship->id
        ]);

        ///----SEND MAIL------
        $receiver_email = $receiver->email;
        // \Mail::send('mail',['sender'=>$sender,'friendship_id' => $friendship->id,'notification_id' => $notification->id], function ($masg) use ($receiver_email) {
        //         $masg->to($receiver_email)
        //             ->subject('SYSTEM');
        //     });
        $data = [
            'sender'=>$sender,
            'friendship_id' => $friendship->id,
            'notification_id' => $notification->id,
            'url'=> url('/api/v1/confirmAddFriend'),
        ];
        /// User mailgun
        /// email test < -- 2solid.system@gmail.com -- >
        // Mail::to('2solid.system@gmail.com')->send(new AcceptFriend($data));

        ///---- main ------
        // Mail::to($receiver_email)->send(new AcceptFriend($data));
        //------------------
        ////----END SEND MAIL-----

        event(new AcceptFriendEvent($notification));

        return $friendship;
    }

    public function confirmAddFriend(array $attribute){
        if($attribute['accept']){

            $noti = Notification::find($attribute['notification_id']);
            if($noti){
                $noti->delete();
            }else{
                return array(
                        'message'=>'The friendship request has comfirm',
                    );
            }
            Friendship::find($attribute['friendship_id'])->update(['status'=>1]);

            event(new AcceptTrueFriend($noti));

            return array(
                'accept' => true,
            );

        }else{

            $noti = Notification::find($attribute['notification_id']);
            if($noti){
                $noti->delete();
            }else{
                return array(
                        'message'=>'The friendship request has comfirm',
                    );
            }
            Friendship::find($attribute['friendship_id'])->delete();

            return array(
                'accept' => false,
            );
        }

    }

    public function getListFriend($id){
        $listIdFriends =[];
        $friendships = Friendship::where(function($query) use ($id) {
                                        $query->where('receiver_id', $id)
                                              ->orWhere('sender_id', $id);
                                    })
                                    ->where('status', 1)
                                    ->get();

        foreach ($friendships as $key => $friendship) {
            if($friendship['sender_id'] == $id){
                array_push($listIdFriends,$friendship['receiver_id']);
            }
            if($friendship['receiver_id'] == $id){
                array_push($listIdFriends,$friendship['sender_id']);
            }
        }

        $listFriends = User::find($listIdFriends);
        foreach ($listFriends as $key => $listFriend) {
            $listFriend->profile;
            $listFriend['avatar'] = $listFriend->getAvatar();
        }
        return $listFriends;
    }

    public function unFriend(array $attribute){
        $friendship = Friendship::where([
            'sender_id'=>$attribute['sender_id'],
            'receiver_id'=>$attribute['receiver_id']
            ])->orWhere([
                'sender_id'=>$attribute['receiver_id'],
                'receiver_id'=>$attribute['sender_id']
            ])->delete();

        event(new UnfriendEvent($attribute));
        return $friendship;
    }

    public function getListEmployees($id){
        $company = Company::where('user_id',$id)->first();
        if($company){
            $members = User::where('company_id',$company['id'])->get();
            foreach ($members as $key => $member) {
                $member['avatar']= $member->getAvatar();
                $member->profile;
            }
        }else{
            $members = null;
        }
        
        return $members;
    }
    public function getListMembers($id){
        $members = User::where('company_id',$id)->get();
        foreach ($members as $key => $member) {
                $member['avatar']= $member->getAvatar();
                $member->profile;
            }
        return $members;
    }
  
    public function getOwner($id){
        $company = Company::find($id);
        $owner = User::find($company['user_id']);
        $owner['avatar'] = $owner->getAvatar();
        return $owner;
    }
  
    public function searchEmailFriend($attributes){
        $searchEmailFriend = User::orderBy('id','DESC')->where('email','LIKE','%'.$attributes['email'].'%')->limit(10)->get();
        foreach ($searchEmailFriend as $key => $user) {
            $user['avatar'] = $user->getAvatar();
            $name = $user->name();
            if($name){
                $user['name'] = $name;
            }else{
                $user['name'] = $user->email;
            }
            
        }
        return $searchEmailFriend;
    }
}
