<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Notification",
 *      required={"content", "receiver", "type"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="content",
 *          description="content",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="status",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="receiver",
 *          description="receiver",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="sender",
 *          description="sender",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="type",
 *          description="type",
 *          type="string"
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
class Notification extends Model
{
    use SoftDeletes;

    public $table = 'notifications';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'content',
        'status',
        'receiver',
        'sender',
        'type',
        'friendship_id',
        'employee_request',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'content' => 'string',
        'status' => 'boolean',
        'receiver' => 'integer',
        'sender' => 'integer',
        'type' => 'string',
        'friendship_id'=>'integer',
        'employee_request'=>'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'content' => 'required',
        'receiver' => 'required',
        'type' => 'required'
    ];

    
}
