<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
/**
 * @SWG\Definition(
 *      definition="Profile",
 *      required={"user_id", "UUID"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="address",
 *          description="address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="city",
 *          description="city",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="phone",
 *          description="phone",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="postal",
 *          description="postal",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="country",
 *          description="country",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="primary",
 *          description="primary",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
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
class Profile extends Model
{
    use SoftDeletes;
    use Uuids;
    public $table = 'profiles';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'address',
        'city_id',
        'phone',
        'postal',
        'country_id',
        'primary',
        'user_id',
        'UUID',
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'address' => 'string',
        'city_id' => 'integer',
        'phone' => 'string',
        'postal' => 'string',
        'country_id' => 'integer',
        'primary' => 'string',
        'user_id' => 'integer',
        'UUID' => 'string',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'max:250',
        'address' => 'max:250',
    ];

    public function breeds(){
        return $this->hasMany(\App\Models\Breed::class);
    }

    public function pets(){
        return $this->hasMany(\App\Models\Pet::class);
    }
    public function user(){
        return $this->belongsTo(\App\Models\User::class);
    }

    public function country(){
        return $this->belongsTo(\App\Models\Country::class);
    }
    public function city(){
        return $this->belongsTo(\App\Models\City::class);
    }

    public function getAvatar()
    {
        $image = \App\Models\Image::where('user_id', $this->id)->where('pet_id', null)->where('is_avatar', 1)->select('url')->first();

        if($image){
            if(\Storage::disk('public')->exists('users/'.$this->id.'/'.$image->url)){
                $url = url('/storage/users/'.$this->id.'/'.$image->url);
            }else{
                $url = url('/storage/default/default-avatar.jpg');
            }
        }else{
            $url = url('/storage/default/default-avatar.jpg');
        }
        return $url;
    }
}
