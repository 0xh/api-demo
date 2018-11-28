<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="FenceData",
 *      required={"fence_id", "long", "lat"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="fence_id",
 *          description="fence_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="long",
 *          description="long",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="lat",
 *          description="lat",
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
class FenceData extends Model
{
    use SoftDeletes;

    public $table = 'fence_datas';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'fence_id',
        'long',
        'lat'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'fence_id' => 'integer',
        'long' => 'float',
        'lat' => 'float'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fence_id' => 'required',
        'long' => 'required',
        'lat' => 'required'
    ];

    
}
