<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
/**
 * @SWG\Definition(
 *      definition="Subscription",
 *      required={"user_id", "UUID", "plan_id", "stripe_id", "stripe_plan", "quantity"},
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
 *          property="UUID",
 *          description="UUID",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="plan_id",
 *          description="plan_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="stripe_id",
 *          description="stripe_id",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="stripe_plan",
 *          description="stripe_plan",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="quantity",
 *          description="quantity",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="trial_ends_at",
 *          description="trial_ends_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="ends_at",
 *          description="ends_at",
 *          type="string",
 *          format="date-time"
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
class Subscription extends Model
{
    use SoftDeletes;
    use Uuids;

    public $table = 'subscriptions';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'user_id',
        'UUID',
        'plan_id',
        'name',
        'stripe_id',
        'stripe_plan',
        'quantity',
        'trial_ends_at',
        'ends_at',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'UUID' => 'string',
        'plan_id' => 'string',
        'name' => 'string',
        'stripe_id' => 'string',
        'stripe_plan' => 'string',
        'quantity' => 'integer',
        'status' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required',
        'plan_id' => 'required',
        'stripe_id' => 'required',
        'stripe_plan' => 'required',
        'quantity' => 'required'
    ];

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function plan(){
        return $this->belongsTo(\App\Models\Plan::class);
    }

}
