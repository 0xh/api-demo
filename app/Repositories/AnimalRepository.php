<?php

namespace App\Repositories;

use App\Models\Animal;
use InfyOm\Generator\Common\BaseRepository;

class AnimalRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'UUID',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Animal::class;
    }
}
