<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
/**
 * @SWG\Definition(
 *      definition="Device",
 *      required={"imei", "user_id", "phone", "UUID"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="imei",
 *          description="imei",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="battery",
 *          description="battery",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="phone",
 *          description="phone",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="mode",
 *          description="mode",
 *          type="boolean"
 *      ),
 *      @SWG\Property(
 *          property="company_id",
 *          description="company_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="UUID",
 *          description="UUID",
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
class Device extends Model
{
    use SoftDeletes;
    use Uuids;

    public $table = 'devices';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'imei',
        'name',
        'user_id',
        'battery',
        'phone',
        'mode',
        'company_id',
        'UUID',
        'product_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'imei' => 'string',
        'name' => 'string',
        'user_id' => 'integer',
        'battery' => 'integer',
        'phone' => 'string',
        'mode' => 'boolean',
        'company_id' => 'integer',
        'UUID' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'imei' =>'required',
        'phone' => 'required',
        'company_id' => 'numeric'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function pets()
    {
        return $this->hasMany(\App\Models\Pet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function jumps()
    {
        return $this->hasMany(\App\Models\Jump::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function rolls()
    {
        return $this->hasMany(\App\Models\Roll::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function locations()
    {
        return $this->hasMany(\App\Models\Location::class);
    }
    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
    public function share(){
        return $this->hasOne(\App\Models\ShareDevice::class);
    }
}
