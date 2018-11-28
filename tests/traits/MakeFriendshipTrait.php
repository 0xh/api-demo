<?php

use Faker\Factory as Faker;
use App\Models\Friendship;
use App\Repositories\FriendshipRepository;

trait MakeFriendshipTrait
{
    /**
     * Create fake instance of Friendship and save it in database
     *
     * @param array $friendshipFields
     * @return Friendship
     */
    public function makeFriendship($friendshipFields = [])
    {
        /** @var FriendshipRepository $friendshipRepo */
        $friendshipRepo = App::make(FriendshipRepository::class);
        $theme = $this->fakeFriendshipData($friendshipFields);
        return $friendshipRepo->create($theme);
    }

    /**
     * Get fake instance of Friendship
     *
     * @param array $friendshipFields
     * @return Friendship
     */
    public function fakeFriendship($friendshipFields = [])
    {
        return new Friendship($this->fakeFriendshipData($friendshipFields));
    }

    /**
     * Get fake data of Friendship
     *
     * @param array $postFields
     * @return array
     */
    public function fakeFriendshipData($friendshipFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'sender_id' => $fake->randomDigitNotNull,
            'receiver_id' => $fake->randomDigitNotNull,
            'status' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $friendshipFields);
    }
}
