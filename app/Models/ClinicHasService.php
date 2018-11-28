<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClinicHasService extends Model
{
    use SoftDeletes;

    public $table = 'clinic_has_service';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'clinic_id',
        'clinic_service_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'clinic_id' => 'integer',
        'clinic_service_id' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'clinic_id' => 'required',
        'clinic_service_id' => 'required'
    ];
    public function service(){
        return $this->belongsTo(\App\Models\Clinic_service::class,'clinic_service_id');
    }

    
}
