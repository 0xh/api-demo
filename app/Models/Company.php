<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
/**
 * @SWG\Definition(
 *      definition="Company",
 *      required={"name", "user_id", "UUID"},
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
 *          property="address",
 *          description="address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="city_id",
 *          description="city_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="country_id",
 *          description="country_id",
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
 *          property="postal",
 *          description="postal",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="subscription_id",
 *          description="subscription_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="UUID",
 *          description="UUID",
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
class Company extends Model
{
    use SoftDeletes;
    use Uuids;

    public $table = 'companies';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'address',
        'city_id',
        'country_id',
        'user_id',
        'postal',
        'description',
        'subscription_id',
        'UUID'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'address' => 'string',
        'city_id' => 'integer',
        'country_id' => 'integer',
        'user_id' => 'integer',
        'postal' => 'string',
        'description' => 'string',
        'subscription_id' => 'integer',
        'UUID' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'address' => 'max:250',
        'user_id' => 'required',
        'postal' => 'max:250',
        'description' => 'max:250',
    ];

    public function employees(){
        return $this->hasMany(\App\Models\User::class);
    }

    public function user(){
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    
    public function ratings(){
        return $this->hasMany(\App\Models\Rating::class);
    }
    public function country(){
        return $this->belongsTo(\App\Models\Country::class);
    }
    public function city(){
        return $this->belongsTo(\App\Models\City::class);
    }

}
