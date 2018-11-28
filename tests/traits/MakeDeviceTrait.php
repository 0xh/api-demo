<?php

use Faker\Factory as Faker;
use App\Models\Device;
use App\Repositories\DeviceRepository;

trait MakeDeviceTrait
{
    /**
     * Create fake instance of Device and save it in database
     *
     * @param array $deviceFields
     * @return Device
     */
    public function makeDevice($deviceFields = [])
    {
        /** @var DeviceRepository $deviceRepo */
        $deviceRepo = App::make(DeviceRepository::class);
        $theme = $this->fakeDeviceData($deviceFields);
        return $deviceRepo->create($theme);
    }

    /**
     * Get fake instance of Device
     *
     * @param array $deviceFields
     * @return Device
     */
    public function fakeDevice($deviceFields = [])
    {
        return new Device($this->fakeDeviceData($deviceFields));
    }

    /**
     * Get fake data of Device
     *
     * @param array $postFields
     * @return array
     */
    public function fakeDeviceData($deviceFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'imei' => $fake->word,
            'name' => $fake->word,
            'user_id' => $fake->randomDigitNotNull,
            'battery' => $fake->randomDigitNotNull,
            'phone' => $fake->randomDigitNotNull,
            'mode' => $fake->word,
            'company_id' => $fake->randomDigitNotNull,
            'UUID' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $deviceFields);
    }
}
