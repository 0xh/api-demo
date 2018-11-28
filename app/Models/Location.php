<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
/**
 * @SWG\Definition(
 *      definition="Location",
 *      required={"device_id", "user_id", "long", "lat", "UUID"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
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
 *          property="user_id",
 *          description="user_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="long",
 *          description="long",
 *          type="number",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="lat",
 *          description="lat",
 *          type="number",
 *          format="float"
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
class Location extends Model
{
    use SoftDeletes;
    use Uuids;
    public $table = 'locations';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'device_id',
        'user_id',
        'long',
        'lat',
        'UUID'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'device_id' => 'integer',
        'user_id' => 'integer',
        'long' => 'float',
        'lat' => 'float',
        'UUID' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'device_id' => 'required|numeric',
        'user_id' => 'required|numeric',
        'long' => 'required|numeric',
        'lat' => 'required|numeric'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function device()
    {
        return $this->belongsTo(\App\Models\Device::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

}
