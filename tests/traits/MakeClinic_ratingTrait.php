<?php

use Faker\Factory as Faker;
use App\Models\Clinic_rating;
use App\Repositories\Clinic_ratingRepository;

trait MakeClinic_ratingTrait
{
    /**
     * Create fake instance of Clinic_rating and save it in database
     *
     * @param array $clinicRatingFields
     * @return Clinic_rating
     */
    public function makeClinic_rating($clinicRatingFields = [])
    {
        /** @var Clinic_ratingRepository $clinicRatingRepo */
        $clinicRatingRepo = App::make(Clinic_ratingRepository::class);
        $theme = $this->fakeClinic_ratingData($clinicRatingFields);
        return $clinicRatingRepo->create($theme);
    }

    /**
     * Get fake instance of Clinic_rating
     *
     * @param array $clinicRatingFields
     * @return Clinic_rating
     */
    public function fakeClinic_rating($clinicRatingFields = [])
    {
        return new Clinic_rating($this->fakeClinic_ratingData($clinicRatingFields));
    }

    /**
     * Get fake data of Clinic_rating
     *
     * @param array $postFields
     * @return array
     */
    public function fakeClinic_ratingData($clinicRatingFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'clinic_id' => $fake->randomDigitNotNull,
            'user_id' => $fake->randomDigitNotNull,
            'content' => $fake->word,
            'score' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $clinicRatingFields);
    }
}
