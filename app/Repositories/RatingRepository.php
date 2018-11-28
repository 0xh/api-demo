<?php

namespace App\Repositories;

use App\Models\Rating;
use InfyOm\Generator\Common\BaseRepository;

class RatingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'company_id',
        'score',
        'comment',
        'user_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Rating::class;
    }
}
