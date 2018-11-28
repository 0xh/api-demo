<?php

use Faker\Factory as Faker;
use App\Models\Jump;
use App\Repositories\JumpRepository;

trait MakeJumpTrait
{
    /**
     * Create fake instance of Jump and save it in database
     *
     * @param array $jumpFields
     * @return Jump
     */
    public function makeJump($jumpFields = [])
    {
        /** @var JumpRepository $jumpRepo */
        $jumpRepo = App::make(JumpRepository::class);
        $theme = $this->fakeJumpData($jumpFields);
        return $jumpRepo->create($theme);
    }

    /**
     * Get fake instance of Jump
     *
     * @param array $jumpFields
     * @return Jump
     */
    public function fakeJump($jumpFields = [])
    {
        return new Jump($this->fakeJumpData($jumpFields));
    }

    /**
     * Get fake data of Jump
     *
     * @param array $postFields
     * @return array
     */
    public function fakeJumpData($jumpFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'device_id' => $fake->randomDigitNotNull,
            'amount' => $fake->randomDigitNotNull,
            'pet_id' => $fake->randomDigitNotNull,
            'UUID' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $jumpFields);
    }
}
