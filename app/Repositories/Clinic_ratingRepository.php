<?php

namespace App\Repositories;

use App\Models\Clinic_rating;
use InfyOm\Generator\Common\BaseRepository;

class Clinic_ratingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'clinic_id',
        'user_id',
        'content',
        'score'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Clinic_rating::class;
    }
}
