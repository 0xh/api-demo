<?php

namespace App\Repositories;

use App\Models\Roll;
use InfyOm\Generator\Common\BaseRepository;

class RollRepository extends BaseRepository
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
        return Roll::class;
    }
}
