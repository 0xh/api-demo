<?php

namespace App\Repositories;

use App\Models\Jump;
use InfyOm\Generator\Common\BaseRepository;

class JumpRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'device_id',
        'amount',
        'pet_id',
        'UUID'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Jump::class;
    }
}
