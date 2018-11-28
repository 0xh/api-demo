<?php

namespace App\Models;

use Eloquent as Model;
/**
 * @SWG\Definition(
 *      definition="Message",
 *      required={"body", "conversation_id", "user_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="body",
 *          description="body",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="conversation_id",
 *          description="conversation_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="read",
 *          description="read",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
class Message extends Model
{

    public $table = 'messages';
    



    public $fillable = [
        'body',
        'conversation_id',
        'user_id',
        'read'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'body' => 'string',
        'conversation_id' => 'integer',
        'user_id' => 'integer',
        'read' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'body' => 'required',
        'conversation_id' => 'required',
        'user_id' => 'required'
    ];
    public function user(){
        return $this->belongsTo(\App\Models\User::class,'user_id');
    }
    public function conversation()
    {
        return $this->belongsTo(\App\Models\Conversation::class, 'conversation_id');
    }

    public function messages_notifications() {
        return $this->hasMany(\App\Models\MessageNortification::class, 'message_id', 'id');
    }
    
}
