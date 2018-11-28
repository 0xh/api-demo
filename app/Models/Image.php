<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
/**
 * @SWG\Definition(
 *      definition="Image",
 *      required={"url", "user_id", "UUID"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="url",
 *          description="url",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="pet_id",
 *          description="pet_id",
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
class Image extends Model
{
    use SoftDeletes;
    use Uuids;
    public $table = 'images';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'url',
        'pet_id',
        'user_id',
        'UUID',
        'is_avatar',
        'product_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'url' => 'string',
        'pet_id' => 'integer',
        'user_id' => 'integer',
        'UUID' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'user_id' => 'required|numeric',
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
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
    public function urlImage(){
        $image = \App\Models\Image::find($this->id);
        if($image){
            if($image['product_id']){
                if(\Storage::disk('public')->exists('products/'.$image['product_id'].'/'.$image->url)){
                    $url = url('/storage/products/'.$image['product_id'].'/'.$image->url);
                }else{
                    $url = url('/img/default/default_image.png');
                }
            }else{
                $url = url('/storage/default/default_image.png');
            }
        }else{
            $url = url('/storage/default/default_image.png');
        }
        return $url;
    }
    public function findImage(){
        $image = \App\Models\Image::find($this->id);
        if($image){
            if($image['user_id'] && !$image['pet_id'] && $image['product_id']){
                $url = 'users/'.$image['user_id'].'/'.$image['url'];
            }else if($image['pet_id'] ){
                $url = 'pets/'.$image['pet_id'].'/'.$image['url'];
            }else if($image['product_id']){
                $url = 'products/'.$image['product_id'].'/'.$image['url'];
            }
        }
        return $url;
    }

    public function getAvatar(){
        
    }
}
