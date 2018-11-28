<?php

use App\Models\Friendship;
use App\Repositories\FriendshipRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FriendshipRepositoryTest extends TestCase
{
    use MakeFriendshipTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var FriendshipRepository
     */
    protected $friendshipRepo;

    public function setUp()
    {
        parent::setUp();
        $this->friendshipRepo = App::make(FriendshipRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateFriendship()
    {
        $friendship = $this->fakeFriendshipData();
        $createdFriendship = $this->friendshipRepo->create($friendship);
        $createdFriendship = $createdFriendship->toArray();
        $this->assertArrayHasKey('id', $createdFriendship);
        $this->assertNotNull($createdFriendship['id'], 'Created Friendship must have id specified');
        $this->assertNotNull(Friendship::find($createdFriendship['id']), 'Friendship with given id must be in DB');
        $this->assertModelData($friendship, $createdFriendship);
    }

    /**
     * @test read
     */
    public function testReadFriendship()
    {
        $friendship = $this->makeFriendship();
        $dbFriendship = $this->friendshipRepo->find($friendship->id);
        $dbFriendship = $dbFriendship->toArray();
        $this->assertModelData($friendship->toArray(), $dbFriendship);
    }

    /**
     * @test update
     */
    public function testUpdateFriendship()
    {
        $friendship = $this->makeFriendship();
        $fakeFriendship = $this->fakeFriendshipData();
        $updatedFriendship = $this->friendshipRepo->update($fakeFriendship, $friendship->id);
        $this->assertModelData($fakeFriendship, $updatedFriendship->toArray());
        $dbFriendship = $this->friendshipRepo->find($friendship->id);
        $this->assertModelData($fakeFriendship, $dbFriendship->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteFriendship()
    {
        $friendship = $this->makeFriendship();
        $resp = $this->friendshipRepo->delete($friendship->id);
        $this->assertTrue($resp);
        $this->assertNull(Friendship::find($friendship->id), 'Friendship should not exist in DB');
    }
}
