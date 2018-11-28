<?php

namespace App\Http\Controllers\Api\V1\Site;

use App\Http\Requests\API\CreateProductAPIRequest;
use App\Http\Requests\API\UpdateProductAPIRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Support\Facades\Storage;
use App\Transformers\Api\V1\Site\ProductTransformer;
/**
 * Class ProductController
 * @package App\Http\Controllers\Api\V1
 */

class ProductAPIController extends AppBaseController
{
    /** @var  ProductRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }


    public function getProducts(){
        $products = $this->productRepository->getProducts();

        return $this->sendResponse($products,'Products retrieved successfully');

    }
    public function showDetailProduct($id){
        $product = $this->productRepository->ShowProduct($id);

        if(!is_null($product) && $product['error']){
            return $this->sendError($product['message']);
        }

        // return (new ProductTransformer)->transform($product);
        return $this->sendResponse($product,'Products retrieved successfully');
    }

}
