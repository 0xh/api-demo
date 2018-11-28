<?php

namespace App\Models;

use Eloquent as Model;

/**
 * @SWG\Definition(
 *      definition="ShareDevice",
 *      required={"user_id", "device_id", "share_by"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
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
 *          property="device_id",
 *          description="device_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="share_by",
 *          description="share_by",
 *          type="integer",
 *          format="int32"
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
class ShareDevice extends Model
{

    public $table = 'share_device';
    
    public $fillable = [
        'user_id',
        'device_id',
        'share_by'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'device_id'=>'integer',
        'share_by'=>'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'device_id' => 'required',
        'share_by'=>'required'
    ];

    public function user(){
    	return $this->belongsTo(\App\Models\User::class,'user_id');
    }
    public function device(){
    	return $this->belongsTo(\App\Models\Device::class,'device_id');
    }

    
}
