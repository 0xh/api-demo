<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="Clinic_rating",
 *      required={"clinic_id", "user_id", "score"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="clinic_id",
 *          description="clinic_id",
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
 *          property="content",
 *          description="content",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="score",
 *          description="score",
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
class Clinic_rating extends Model
{
    use SoftDeletes;

    public $table = 'clinic_ratings';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'clinic_id',
        'user_id',
        'content',
        'score'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'clinic_id' => 'integer',
        'user_id' => 'integer',
        'content' => 'string',
        'score' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'clinic_id' => 'required',
        'user_id' => 'required',
        'score' => 'required'
    ];
    public function user(){
        return $this->belongsTo(\App\Models\User::class);
    }
    
}
