<?php

use Faker\Factory as Faker;
use App\Models\Clinic;
use App\Repositories\ClinicRepository;

trait MakeClinicTrait
{
    /**
     * Create fake instance of Clinic and save it in database
     *
     * @param array $clinicFields
     * @return Clinic
     */
    public function makeClinic($clinicFields = [])
    {
        /** @var ClinicRepository $clinicRepo */
        $clinicRepo = App::make(ClinicRepository::class);
        $theme = $this->fakeClinicData($clinicFields);
        return $clinicRepo->create($theme);
    }

    /**
     * Get fake instance of Clinic
     *
     * @param array $clinicFields
     * @return Clinic
     */
    public function fakeClinic($clinicFields = [])
    {
        return new Clinic($this->fakeClinicData($clinicFields));
    }

    /**
     * Get fake data of Clinic
     *
     * @param array $postFields
     * @return array
     */
    public function fakeClinicData($clinicFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'description' => $fake->word,
            'address' => $fake->word,
            'phone' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $clinicFields);
    }
}
