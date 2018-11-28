<?php

namespace App\Transformers\Api\V1\Site;

use League\Fractal\TransformerAbstract;
use App\Models\Product;

/**
 * Class ProductTransformer
 * @package namespace App\Transformers\Api\V1\Site;
 */
class ProductTransformer extends TransformerAbstract
{

    /**
     * Transform the \Product entity
     * @param \Product $model
     *
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'id'         => (int) $product->id,
            'name'       => (string) $product->name,
            'price'      => $product->price,
            'description'=> $product->description,
            'url'        => $product->getAvatar(),
            'images'     => $this->urlImages($product->images)
        ];
    }
    public function urlImages($images){
        $urlImages = null;
        foreach ($images as $key => $image) {

            if(\Storage::disk('public')->exists('products/'.$image->product_id.'/'.$image->url)){
                $url = url('/storage/products/'.$image->product_id.'/'.$image->url);
            }else{
                $url = url('/img/default/default_image.png');
            }
             $urlImages[$key] = $url;
        }
        return $urlImages;
    }
}
