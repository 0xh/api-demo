<?php

use App\Models\Smile;
use App\Repositories\SmileRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SmileRepositoryTest extends TestCase
{
    use MakeSmileTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var SmileRepository
     */
    protected $smileRepo;

    public function setUp()
    {
        parent::setUp();
        $this->smileRepo = App::make(SmileRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateSmile()
    {
        $smile = $this->fakeSmileData();
        $createdSmile = $this->smileRepo->create($smile);
        $createdSmile = $createdSmile->toArray();
        $this->assertArrayHasKey('id', $createdSmile);
        $this->assertNotNull($createdSmile['id'], 'Created Smile must have id specified');
        $this->assertNotNull(Smile::find($createdSmile['id']), 'Smile with given id must be in DB');
        $this->assertModelData($smile, $createdSmile);
    }

    /**
     * @test read
     */
    public function testReadSmile()
    {
        $smile = $this->makeSmile();
        $dbSmile = $this->smileRepo->find($smile->id);
        $dbSmile = $dbSmile->toArray();
        $this->assertModelData($smile->toArray(), $dbSmile);
    }

    /**
     * @test update
     */
    public function testUpdateSmile()
    {
        $smile = $this->makeSmile();
        $fakeSmile = $this->fakeSmileData();
        $updatedSmile = $this->smileRepo->update($fakeSmile, $smile->id);
        $this->assertModelData($fakeSmile, $updatedSmile->toArray());
        $dbSmile = $this->smileRepo->find($smile->id);
        $this->assertModelData($fakeSmile, $dbSmile->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteSmile()
    {
        $smile = $this->makeSmile();
        $resp = $this->smileRepo->delete($smile->id);
        $this->assertTrue($resp);
        $this->assertNull(Smile::find($smile->id), 'Smile should not exist in DB');
    }
}
