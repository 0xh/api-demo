<?php

namespace App\Repositories;

use App\Models\Smile;
use InfyOm\Generator\Common\BaseRepository;

class SmileRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'amount',
        'device_id',
        'pet_id',
        'UUID'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Smile::class;
    }
}
