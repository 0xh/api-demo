<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetAssign extends Model
{
    public $table = 'pet_assigns';
    
    public $fillable = [
        'pet_id',
        'company_id',
        'walker_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'pet_id' => 'integer',
        'company_id'=>'integer',
        'walker_id'=>'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'pet_id' => 'required',
        'company_id' => 'required',
    ];
    public function user(){
    	$pet = \App\Models\Pet::select('id','name','user_id','UUID')->where('id',$this->pet_id)->first();

    	$pet['user'] = $pet->user()->select('id','email')->first()->toArray();

    	$pet['url']  = $pet->getAvatar();

    	return $pet;
    }
    public function walker(){
    	$user = \App\Models\User::find($this->walker_id);
    	if($user->profile){
    		$walker['name'] = $user->profile->name;
    		$walker['id'] = $user->id;
    	}else{
    		$walker['name'] = $user->email;
    		$walker['id'] = $user->id;
    	}
    	return $walker;
    }
}
