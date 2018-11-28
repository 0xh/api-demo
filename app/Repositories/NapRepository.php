<?php

namespace App\Repositories;

use App\Models\Nap;
use InfyOm\Generator\Common\BaseRepository;

class NapRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'amount',
        'pet_id',
        'device_id',
        'UUID'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Nap::class;
    }
}
