<?php

use Faker\Factory as Faker;
use App\Models\Smile;
use App\Repositories\SmileRepository;

trait MakeSmileTrait
{
    /**
     * Create fake instance of Smile and save it in database
     *
     * @param array $smileFields
     * @return Smile
     */
    public function makeSmile($smileFields = [])
    {
        /** @var SmileRepository $smileRepo */
        $smileRepo = App::make(SmileRepository::class);
        $theme = $this->fakeSmileData($smileFields);
        return $smileRepo->create($theme);
    }

    /**
     * Get fake instance of Smile
     *
     * @param array $smileFields
     * @return Smile
     */
    public function fakeSmile($smileFields = [])
    {
        return new Smile($this->fakeSmileData($smileFields));
    }

    /**
     * Get fake data of Smile
     *
     * @param array $postFields
     * @return array
     */
    public function fakeSmileData($smileFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'amount' => $fake->word,
            'device_id' => $fake->randomDigitNotNull,
            'pet_id' => $fake->randomDigitNotNull,
            'UUID' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $smileFields);
    }
}
