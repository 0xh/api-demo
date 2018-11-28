<?php

namespace App\Repositories;

use App\Models\Notification;
use InfyOm\Generator\Common\BaseRepository;

class NotificationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'content',
        'status',
        'receiver',
        'sender',
        'type'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Notification::class;
    }
    public function getNotifications($id){
        $notifications = Notification::orderBy('id','DESC')->where('receiver',$id)->paginate(4);
        $countNoti = Notification::where('receiver',$id)->where('is_seen',false)->count();

        return [$countNoti,$notifications];
    }
    public function checkReadNotification($id){
        $notification = Notification::find($id);
        $notification->status = true;
        $notification->save();
        return $notification;
    }
    public function checkSeenNotification($id){
        $notifications = Notification::where('receiver',$id)->update(['is_seen' => true]);
        return $notifications;
    }
}
