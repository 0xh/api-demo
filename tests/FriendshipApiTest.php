<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FriendshipApiTest extends TestCase
{
    use MakeFriendshipTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateFriendship()
    {
        $friendship = $this->fakeFriendshipData();
        $this->json('POST', '/api/v1/friendships', $friendship);

        $this->assertApiResponse($friendship);
    }

    /**
     * @test
     */
    public function testReadFriendship()
    {
        $friendship = $this->makeFriendship();
        $this->json('GET', '/api/v1/friendships/'.$friendship->id);

        $this->assertApiResponse($friendship->toArray());
    }

    /**
     * @test
     */
    public function testUpdateFriendship()
    {
        $friendship = $this->makeFriendship();
        $editedFriendship = $this->fakeFriendshipData();

        $this->json('PUT', '/api/v1/friendships/'.$friendship->id, $editedFriendship);

        $this->assertApiResponse($editedFriendship);
    }

    /**
     * @test
     */
    public function testDeleteFriendship()
    {
        $friendship = $this->makeFriendship();
        $this->json('DELETE', '/api/v1/friendships/'.$friendship->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/friendships/'.$friendship->id);

        $this->assertResponseStatus(404);
    }
}
