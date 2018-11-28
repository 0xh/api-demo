<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;

/**
 * @SWG\Definition(
 *      definition="Smile",
 *      required={"amount", "device_id", "pet_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="amount",
 *          description="amount",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="device_id",
 *          description="device_id",
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
class Smile extends Model
{
    use SoftDeletes;
    use Uuids;

    public $table = 'smiles';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'amount',
        'device_id',
        'pet_id',
        'UUID'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'integer',
        'device_id' => 'integer',
        'pet_id' => 'integer',
        'UUID' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'amount' => 'required',
        'device_id' => 'required',
        'pet_id' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function pet()
    {
        return $this->belongsTo(\App\Models\Pet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function device()
    {
        return $this->belongsTo(\App\Models\Device::class);
    }
}
