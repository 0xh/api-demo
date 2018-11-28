<?php

use Faker\Factory as Faker;
use App\Models\Clinic_service;
use App\Repositories\Clinic_serviceRepository;

trait MakeClinic_serviceTrait
{
    /**
     * Create fake instance of Clinic_service and save it in database
     *
     * @param array $clinicServiceFields
     * @return Clinic_service
     */
    public function makeClinic_service($clinicServiceFields = [])
    {
        /** @var Clinic_serviceRepository $clinicServiceRepo */
        $clinicServiceRepo = App::make(Clinic_serviceRepository::class);
        $theme = $this->fakeClinic_serviceData($clinicServiceFields);
        return $clinicServiceRepo->create($theme);
    }

    /**
     * Get fake instance of Clinic_service
     *
     * @param array $clinicServiceFields
     * @return Clinic_service
     */
    public function fakeClinic_service($clinicServiceFields = [])
    {
        return new Clinic_service($this->fakeClinic_serviceData($clinicServiceFields));
    }

    /**
     * Get fake data of Clinic_service
     *
     * @param array $postFields
     * @return array
     */
    public function fakeClinic_serviceData($clinicServiceFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->word,
            'note' => $fake->text,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $clinicServiceFields);
    }
}
