<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRequest extends Model
{
    public $table = 'employee_request';
    
    public $fillable = [
        'user_id',
        'company_id',
        'status'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'company_id'=>'integer',
        'status'=>'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'email' => 'required',
        'company_id' => 'required',
    ];
}
