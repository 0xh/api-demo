<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
/**
 * @SWG\Definition(
 *      definition="Jump",
 *      required={"device_id", "amount", "pet_id", "UUID"},
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
 *          property="amount",
 *          description="amount",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="pet_id",
 *          description="pet_id",
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
class Jump extends Model
{
    use SoftDeletes;
    use Uuids;
    public $table = 'jumps';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'device_id',
        'amount',
        'pet_id',
        'UUID'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'device_id' => 'integer',
        'amount' => 'integer',
        'pet_id' => 'integer',
        'UUID' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'device_id' => 'required|numeric',
        'amount' => 'required|numeric',
        'pet_id' => 'required|numeric',
        // 'UUID' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function device()
    {
        return $this->belongsTo(\App\Models\Device::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function pet()
    {
        return $this->belongsTo(\App\Models\Pet::class);
    }
}
