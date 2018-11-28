<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Pet_fence",
 *      required={"pet_id", "fence_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
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
 *          property="fence_id",
 *          description="fence_id",
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
class Pet_fence extends Model
{
    use SoftDeletes;

    public $table = 'pet_fences';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'pet_id',
        'fence_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'pet_id' => 'integer',
        'fence_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pet_id' => 'required',
        'fence_id' => 'required'
    ];
    public function fence(){
        return $this->belongsTo(\App\Models\Fence::class,'fence_id');
    }
    public function pet(){
        return $this->belongsTo(\App\Models\Pet::class);
    }

    
}
