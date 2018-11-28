<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
use App\Models\Pet_fence;
/**
 * @SWG\Definition(
 *      definition="Pet",
 *      required={"name", "device_id"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="device_id",
 *          description="device_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="animal_id",
 *          description="animal_id",
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
class Pet extends Model
{
    use SoftDeletes;
    use Uuids;
    public $table = 'pets';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'description',
        'device_id',
        'animal_id',
        'UUID',
        'user_id',
        'breed_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'device_id' => 'integer',
        'animal_id' => 'integer',
        'UUID' => 'string',
        'user_id' => 'integer',
        'breed_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'user_id' => 'required',
        'animal_id' => 'required',
        'breed_id' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function device()
    {
        return $this->belongsTo(\App\Models\Device::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function breed()
    {
        return $this->belongsTo(\App\Models\Breed::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function animal()
    {
        return $this->belongsTo(\App\Models\Animal::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     **/
    public function jumps()
    {
        return $this->hasMany(\App\Models\Jump::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     **/
    public function rolls()
    {
        return $this->hasMany(\App\Models\Roll::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     **/
    public function smiles()
    {
        return $this->hasMany(\App\Models\Smile::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     **/
    public function naps()
    {
        return $this->hasMany(\App\Models\Nap::class);
    }
    public function image()
    {
        return $this->hasOne(\App\Models\Image::class);
    }
    public function pet_fence(){
        return $this->hasOne(\App\Models\Pet_fence::class);
    }
    public function hasFence(){
        $hasFence = Pet_fence::where('pet_id',$this->id)->get();
        return $hasFence;
    }

    public function getAvatar()
    {
        $image = \App\Models\Image::where('pet_id', $this->id)->where('is_avatar', 1)->select('url')->first();
        if($image){
            if(\Storage::disk('public')->exists('pets/'.$this->id.'/'.$image->url)){
                $url = url('/storage/pets/'.$this->id.'/'.$image->url);
            }
            else{
                $url = url('/img/default/default-avatar-pet.png');
            }
        }else{
            $url = url('/img/default/default-avatar-pet.png');
        }
        return $url;
    }

    public function fence(){
        return $this->belongsTo(\App\Models\Fence::class);
    }
}
