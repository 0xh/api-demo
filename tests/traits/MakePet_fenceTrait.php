<?php

use Faker\Factory as Faker;
use App\Models\Pet_fence;
use App\Repositories\Pet_fenceRepository;

trait MakePet_fenceTrait
{
    /**
     * Create fake instance of Pet_fence and save it in database
     *
     * @param array $petFenceFields
     * @return Pet_fence
     */
    public function makePet_fence($petFenceFields = [])
    {
        /** @var Pet_fenceRepository $petFenceRepo */
        $petFenceRepo = App::make(Pet_fenceRepository::class);
        $theme = $this->fakePet_fenceData($petFenceFields);
        return $petFenceRepo->create($theme);
    }

    /**
     * Get fake instance of Pet_fence
     *
     * @param array $petFenceFields
     * @return Pet_fence
     */
    public function fakePet_fence($petFenceFields = [])
    {
        return new Pet_fence($this->fakePet_fenceData($petFenceFields));
    }

    /**
     * Get fake data of Pet_fence
     *
     * @param array $postFields
     * @return array
     */
    public function fakePet_fenceData($petFenceFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'pet_id' => $fake->randomDigitNotNull,
            'fence_id' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $petFenceFields);
    }
}
