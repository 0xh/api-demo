<?php

namespace App\Repositories;

use App\Models\FenceData;
use InfyOm\Generator\Common\BaseRepository;

class FenceDataRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fence_id',
        'long',
        'lat'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return FenceData::class;
    }
}
