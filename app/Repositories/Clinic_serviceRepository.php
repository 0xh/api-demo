<?php

namespace App\Repositories;

use App\Models\Clinic_service;
use InfyOm\Generator\Common\BaseRepository;

class Clinic_serviceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'note'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Clinic_service::class;
    }
}
