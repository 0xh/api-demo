<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Emadadly\LaravelUuid\Uuids;
/**
 * @SWG\Definition(
 *      definition="Product",
 *      required={"name", "price", "UUID"},
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
 *          property="price",
 *          description="price",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="category_id",
 *          description="category_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="sku",
 *          description="sku",
 *          type="string"
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
class Product extends Model
{
    use SoftDeletes;
    use Uuids;
    public $table = 'products';
    

    protected $dates = ['deleted_at'];


    public $fillable = [
        'name',
        'price',
        'description',
        'category_id',
        'sku',
        'UUID'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string',
        'price' => 'integer',
        'description' => 'string',
        'category_id' => 'integer',
        'sku' => 'string',
        'UUID' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required',
        'price' => 'required'
    ];

    public function images(){
        return $this->hasMany(\App\Models\Image::class);
    }

    public function devices(){
        return $this->hasMany(\App\Models\Device::class);
    }

    public function carts()
    {
        return $this->hasMany(\App\Models\Cart::class);
    }
  
    public function category(){
        return $this->belongsTo(\App\Models\Category::class);
    }

    public function getAvatar()
    {
        $image = \App\Models\Image::where('product_id', $this->id)->where('pet_id', null)->select('url')->first();

        if($image){
            if(\Storage::disk('public')->exists('products/'.$this->id.'/'.$image->url)){
                $url = url('/storage/products/'.$this->id.'/'.$image->url);
            }else{
                $url = url('/img/default/default_image.png');
            }
        }else{
            $url = url('/img/default/default_image.png');
        }
        return $url;
    }

}
