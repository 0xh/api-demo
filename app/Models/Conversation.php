<?php

namespace App\Models;

use Eloquent as Model;
/**
 * @SWG\Definition(
 *      definition="Conversation",
 *      required={"name", "author_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="author_id",
 *          description="author_id",
 *          type="integer",
 *          format="int32"
 *      )
 * )
 */
class Conversation extends Model
{

    public $table = 'conversations';



    public $fillable = [
        'name',
        'author_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'author_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'author_id' => 'required'
    ];

    // public function messages(){
    //     return $this->hasMany(\App\Models\Message::class);
    // }
    public function conversation_users(){
        return $this->hasMany(\App\Models\ConversationUsers::class);
    }


    public function users() {
        return $this->belongsToMany(\App\Models\User::class, 'conversation_users', 'conversation_id', 'user_id');
    }

    public function messages() {
        return $this->hasMany(\App\Models\Message::class, 'conversation_id', 'id')->orderBy('id','DESC')->limit(20);
    }

    public function messagesNotifications() {
        return $this->hasMany(\App\Models\MessageNortification::class, 'conversation_id', 'id')->where('read', 0)->where('user_id', \Auth::user()['id']);
    }

    public function countMessageNotRead($id){
        $message_not_read = \App\Models\MessageNortification::where('conversation_id', $this->id)->where('read', 0)->where('user_id',$id)->count();
        return $message_not_read;
    }
    public function checkHasPrivateConversation($attributes){
        $conversations = Conversation::where('author_id',$attributes[0])->orWhere('author_id',$attributes[1])->get();
        foreach ($conversations as $key => $conversation) {
            # code...
        }
        $check = $this->hasMany(\App\Models\ConversationUsers::class);
        $plucked =  $check->pluck('id')->toArray();
        if(sort($plucked) == sort($attributes)){
            return true;
        }
        return false;

    }
}
