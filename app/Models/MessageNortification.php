<?php

namespace App\Models;

use Eloquent as Model;
/**
 * @SWG\Definition(
 *      definition="MessageNortification",
 *      required={"user_id", "conversation_id", "message_id", "read"},
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
 *          property="message_id",
 *          description="message_id",
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
class MessageNortification extends Model
{

    public $table = 'message_notifications';
    
    public $fillable = [
        'user_id',
        'conversation_id',
        'message_id',
        'read'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'conversation_id' => 'integer',
        'message_id' => 'integer',
        'read' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'conversation_id' => 'required',
        'message_id' => 'required',
        'conversation_id' => 'required'
    ];
    // public function user(){
    //     return $this->belongsTo(\App\Models\User::class,'user_id');
    // }
    
    
}