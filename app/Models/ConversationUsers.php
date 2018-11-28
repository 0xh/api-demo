<?php

namespace App\Models;

use Eloquent as Model;

class ConversationUsers extends Model
{

    public $table = 'conversation_users';
    
    public $fillable = [
        'user_id',
        'conversation_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'conversation_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'conversation_id' => 'required'
    ];
    // public function user(){
    //     return $this->belongsTo(\App\Models\User::class,'user_id');
    // }
    
    
}
