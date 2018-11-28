<?php

use Faker\Factory as Faker;
use App\Models\Notification;
use App\Repositories\NotificationRepository;

trait MakeNotificationTrait
{
    /**
     * Create fake instance of Notification and save it in database
     *
     * @param array $notificationFields
     * @return Notification
     */
    public function makeNotification($notificationFields = [])
    {
        /** @var NotificationRepository $notificationRepo */
        $notificationRepo = App::make(NotificationRepository::class);
        $theme = $this->fakeNotificationData($notificationFields);
        return $notificationRepo->create($theme);
    }

    /**
     * Get fake instance of Notification
     *
     * @param array $notificationFields
     * @return Notification
     */
    public function fakeNotification($notificationFields = [])
    {
        return new Notification($this->fakeNotificationData($notificationFields));
    }

    /**
     * Get fake data of Notification
     *
     * @param array $postFields
     * @return array
     */
    public function fakeNotificationData($notificationFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'content' => $fake->word,
            'status' => $fake->word,
            'receiver' => $fake->randomDigitNotNull,
            'sender' => $fake->randomDigitNotNull,
            'type' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $notificationFields);
    }
}
