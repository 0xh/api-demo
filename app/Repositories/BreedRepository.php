<?php

namespace App\Repositories;

use App\Models\Breed;
use InfyOm\Generator\Common\BaseRepository;

class BreedRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'user_id',
        'animal_id',
        'UUID'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Breed::class;
    }

    public function all($columns = ['*']){
        $breeds = Breed::orderBy('id', 'DESC')->with(['animal' => function ($query){
            $query->select('id', 'name');
        }])->get();

        return $breeds;
    }
}
