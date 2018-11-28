<?php

use Faker\Factory as Faker;
use App\Models\Roll;
use App\Repositories\RollRepository;

trait MakeRollTrait
{
    /**
     * Create fake instance of Roll and save it in database
     *
     * @param array $rollFields
     * @return Roll
     */
    public function makeRoll($rollFields = [])
    {
        /** @var RollRepository $rollRepo */
        $rollRepo = App::make(RollRepository::class);
        $theme = $this->fakeRollData($rollFields);
        return $rollRepo->create($theme);
    }

    /**
     * Get fake instance of Roll
     *
     * @param array $rollFields
     * @return Roll
     */
    public function fakeRoll($rollFields = [])
    {
        return new Roll($this->fakeRollData($rollFields));
    }

    /**
     * Get fake data of Roll
     *
     * @param array $postFields
     * @return array
     */
    public function fakeRollData($rollFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'amount' => $fake->randomDigitNotNull,
            'device_id' => $fake->randomDigitNotNull,
            'pet_id' => $fake->randomDigitNotNull,
            'UUID' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $rollFields);
    }
}
