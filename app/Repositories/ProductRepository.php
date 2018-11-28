<?php

namespace App\Repositories;

use App\Models\Product;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\Storage;
class ProductRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'price',
        'description',
        'category_id',
        'sku',
        'UUID'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Product::class;
    }

    public function getProducts(){
        $products = Product::orderBy('id', 'DESC')->with('category')->get();

        $arrProducts = $products->toArray();

        foreach ($products as $key => $product) {
            $avatar = $product->getAvatar();
            $arrProducts[$key]['url'] = $avatar;
            if($product->images){
                $arrProducts[$key]['urlImage'] = $product->images;
                foreach ($product->images as $keyImage =>  $image) {
                    $arrProducts[$key]['urlImage'][$keyImage]['url']= $image->urlImage();
                }
            }
        }

        return $arrProducts;
    }

    public function ShowProduct($id){
        $product = Product::find($id);
        if($product->images){
           foreach ($product->images as $key => $image) {
                $image->url;
                if(\Storage::disk('public')->exists('products/'.$product->id.'/'.$image->url)){
                    $url = url('/storage/products/'.$product->id.'/'.$image->url);
                }else{
                    $url = url('/img/default/default_image.png');
                }
                $product['images'][$key]['url'] = $url;
            } 
        }
        return $product;
    }


    public function updateProduct(array $attributes,$id){

        $product = Product::find($id);
        $product->update($attributes); 

        if($attributes['multiFile']){
            foreach ($attributes['multiFile'] as $key => $file) {
                $ext        = $file->guessClientExtension();
                $reName     = time();
                $rand       = rand(1,999999);
                $path   = $file->storeAs('products/'.$product['id'],$reName.$rand.'.'.$ext);
                $product->images()->create([
                        'url'=>$reName.$rand.'.'.$ext,
                        'product_id'=>$product['id'],
                        'is_avatar'=>false
                    ]);
            }
        }
        $avatar = $product->getAvatar();
        $product['url'] = $avatar;
        if($product->images){
            $product['urlImage'] = $product->images;
            foreach ($product->images as $keyImage =>  $image) {
                $product['urlImage'][$keyImage]['url']= $image->urlImage();
                
            }
        }
        return $product;

    }
    public function createProduct(array $attributes){
        $product = Product::create($attributes);

        if($attributes['multiFile']){
            foreach ($attributes['multiFile'] as $key => $file) {
                $ext        = $file->guessClientExtension();
                $reName     = time();
                $rand       = rand(1,999999);
                $path   = $file->storeAs('products/'.$product->id,$reName.$rand.'.'.$ext);
                $product->images()->create([
                        'url'=>$reName.$rand.'.'.$ext,
                        'product_id'=>$product->id,
                        'is_avatar'=>false
                    ]);
            }
        }
        
        $avatar = $product->getAvatar();
        $product['url'] = $avatar;
        if($product->images){
            $product['urlImage'] = $product->images;
            foreach ($product->images as $keyImage =>  $image) {
                $product['urlImage'][$keyImage]['url']= $image->urlImage();
                
            }
        }
        return $product;
    }

}
