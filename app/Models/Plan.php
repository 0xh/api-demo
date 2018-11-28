<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
/**
 * @SWG\Definition(
 *      definition="Plan",
 *      required={"title", "amount", "currency", "interval"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="amount",
 *          description="amount",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="currency",
 *          description="currency",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="interval",
 *          description="interval",
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
class Plan extends Model
{
    use SoftDeletes;

    public $table = 'plans';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'description',
        'amount',
        'currency',
        'interval',
        'interval_count',
        'trial_period_days',
        'plan_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'amount' => 'string',
        'currency' => 'string',
        'interval' => 'string',
        'interval_count' => 'integer',
        'trial_period_days' => 'integer',
        'plan_id' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'amount' => 'required',
        'currency' => 'required',
        'interval' => 'required',
        'plan_id' => 'required'
    ];

    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class);
    }
}
