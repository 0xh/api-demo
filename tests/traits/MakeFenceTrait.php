<?php

use Faker\Factory as Faker;
use App\Models\Fence;
use App\Repositories\FenceRepository;

trait MakeFenceTrait
{
    /**
     * Create fake instance of Fence and save it in database
     *
     * @param array $fenceFields
     * @return Fence
     */
    public function makeFence($fenceFields = [])
    {
        /** @var FenceRepository $fenceRepo */
        $fenceRepo = App::make(FenceRepository::class);
        $theme = $this->fakeFenceData($fenceFields);
        return $fenceRepo->create($theme);
    }

    /**
     * Get fake instance of Fence
     *
     * @param array $fenceFields
     * @return Fence
     */
    public function fakeFence($fenceFields = [])
    {
        return new Fence($this->fakeFenceData($fenceFields));
    }

    /**
     * Get fake data of Fence
     *
     * @param array $postFields
     * @return array
     */
    public function fakeFenceData($fenceFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'user_id' => $fake->randomDigitNotNull,
            'company_id' => $fake->randomDigitNotNull,
            'geo_data' => $fake->text,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $fenceFields);
    }
}
