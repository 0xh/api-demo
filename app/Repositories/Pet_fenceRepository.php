<?php

namespace App\Repositories;

use App\Models\Pet_fence;
use InfyOm\Generator\Common\BaseRepository;

class Pet_fenceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'pet_id',
        'fence_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Pet_fence::class;
    }
}
