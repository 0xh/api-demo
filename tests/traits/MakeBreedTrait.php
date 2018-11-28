<?php

use Faker\Factory as Faker;
use App\Models\Breed;
use App\Repositories\BreedRepository;

trait MakeBreedTrait
{
    /**
     * Create fake instance of Breed and save it in database
     *
     * @param array $breedFields
     * @return Breed
     */
    public function makeBreed($breedFields = [])
    {
        /** @var BreedRepository $breedRepo */
        $breedRepo = App::make(BreedRepository::class);
        $theme = $this->fakeBreedData($breedFields);
        return $breedRepo->create($theme);
    }

    /**
     * Get fake instance of Breed
     *
     * @param array $breedFields
     * @return Breed
     */
    public function fakeBreed($breedFields = [])
    {
        return new Breed($this->fakeBreedData($breedFields));
    }

    /**
     * Get fake data of Breed
     *
     * @param array $postFields
     * @return array
     */
    public function fakeBreedData($breedFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'description' => $fake->text,
            'user_id' => $fake->randomDigitNotNull,
            'animal_id' => $fake->randomDigitNotNull,
            'UUID' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $breedFields);
    }
}
