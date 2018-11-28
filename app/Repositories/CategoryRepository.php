<?php

namespace App\Repositories;

use App\Models\Category;
use InfyOm\Generator\Common\BaseRepository;

class CategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'markup',
        'category_id',
        'UUID'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Category::class;
    }

    public function getCategories(){
        $categories = Category::orderBy('id','ASC')->get();
        $arrCategories = $categories->toArray();
        foreach ($categories as $key => $value) {
            $hasCategories = $value->hasCategories->toArray();
            if($hasCategories){
                $arrCategories[$key]['has_categories'] = $hasCategories;
            };
            $parent = $value->parent;
            if($parent){
                $arrCategories[$key]['parent'] = $parent->toArray();
            };
        }
        return $arrCategories;

    }
}
