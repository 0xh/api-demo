<?php

use Faker\Factory as Faker;
use App\Models\Nap;
use App\Repositories\NapRepository;

trait MakeNapTrait
{
    /**
     * Create fake instance of Nap and save it in database
     *
     * @param array $napFields
     * @return Nap
     */
    public function makeNap($napFields = [])
    {
        /** @var NapRepository $napRepo */
        $napRepo = App::make(NapRepository::class);
        $theme = $this->fakeNapData($napFields);
        return $napRepo->create($theme);
    }

    /**
     * Get fake instance of Nap
     *
     * @param array $napFields
     * @return Nap
     */
    public function fakeNap($napFields = [])
    {
        return new Nap($this->fakeNapData($napFields));
    }

    /**
     * Get fake data of Nap
     *
     * @param array $postFields
     * @return array
     */
    public function fakeNapData($napFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'amount' => $fake->randomDigitNotNull,
            'pet_id' => $fake->randomDigitNotNull,
            'device_id' => $fake->randomDigitNotNull,
            'UUID' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $napFields);
    }
}
