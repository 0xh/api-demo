<?php

use Faker\Factory as Faker;
use App\Models\Pet;
use App\Repositories\PetRepository;

trait MakePetTrait
{
    /**
     * Create fake instance of Pet and save it in database
     *
     * @param array $petFields
     * @return Pet
     */
    public function makePet($petFields = [])
    {
        /** @var PetRepository $petRepo */
        $petRepo = App::make(PetRepository::class);
        $theme = $this->fakePetData($petFields);
        return $petRepo->create($theme);
    }

    /**
     * Get fake instance of Pet
     *
     * @param array $petFields
     * @return Pet
     */
    public function fakePet($petFields = [])
    {
        return new Pet($this->fakePetData($petFields));
    }

    /**
     * Get fake data of Pet
     *
     * @param array $postFields
     * @return array
     */
    public function fakePetData($petFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'description' => $fake->text,
            'device_id' => $fake->randomDigitNotNull,
            'animal_id' => $fake->randomDigitNotNull,
            'UUID' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $petFields);
    }
}
