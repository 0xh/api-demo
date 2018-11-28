<?php

use Faker\Factory as Faker;
use App\Models\Animal;
use App\Repositories\AnimalRepository;

trait MakeAnimalTrait
{
    /**
     * Create fake instance of Animal and save it in database
     *
     * @param array $animalFields
     * @return Animal
     */
    public function makeAnimal($animalFields = [])
    {
        /** @var AnimalRepository $animalRepo */
        $animalRepo = App::make(AnimalRepository::class);
        $theme = $this->fakeAnimalData($animalFields);
        return $animalRepo->create($theme);
    }

    /**
     * Get fake instance of Animal
     *
     * @param array $animalFields
     * @return Animal
     */
    public function fakeAnimal($animalFields = [])
    {
        return new Animal($this->fakeAnimalData($animalFields));
    }

    /**
     * Get fake data of Animal
     *
     * @param array $postFields
     * @return array
     */
    public function fakeAnimalData($animalFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'description' => $fake->text,
            'UUID' => $fake->word,
            'user_id' => $fake->randomDigitNotNull,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $animalFields);
    }
}
