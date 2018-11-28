<?php

use Faker\Factory as Faker;
use App\Models\FenceData;
use App\Repositories\FenceDataRepository;

trait MakeFenceDataTrait
{
    /**
     * Create fake instance of FenceData and save it in database
     *
     * @param array $fenceDataFields
     * @return FenceData
     */
    public function makeFenceData($fenceDataFields = [])
    {
        /** @var FenceDataRepository $fenceDataRepo */
        $fenceDataRepo = App::make(FenceDataRepository::class);
        $theme = $this->fakeFenceDataData($fenceDataFields);
        return $fenceDataRepo->create($theme);
    }

    /**
     * Get fake instance of FenceData
     *
     * @param array $fenceDataFields
     * @return FenceData
     */
    public function fakeFenceData($fenceDataFields = [])
    {
        return new FenceData($this->fakeFenceDataData($fenceDataFields));
    }

    /**
     * Get fake data of FenceData
     *
     * @param array $postFields
     * @return array
     */
    public function fakeFenceDataData($fenceDataFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'fence_id' => $fake->randomDigitNotNull,
            'long' => $fake->word,
            'lat' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $fenceDataFields);
    }
}
