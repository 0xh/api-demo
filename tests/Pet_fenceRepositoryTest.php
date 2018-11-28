<?php

use App\Models\Pet_fence;
use App\Repositories\Pet_fenceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Pet_fenceRepositoryTest extends TestCase
{
    use MakePet_fenceTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var Pet_fenceRepository
     */
    protected $petFenceRepo;

    public function setUp()
    {
        parent::setUp();
        $this->petFenceRepo = App::make(Pet_fenceRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatePet_fence()
    {
        $petFence = $this->fakePet_fenceData();
        $createdPet_fence = $this->petFenceRepo->create($petFence);
        $createdPet_fence = $createdPet_fence->toArray();
        $this->assertArrayHasKey('id', $createdPet_fence);
        $this->assertNotNull($createdPet_fence['id'], 'Created Pet_fence must have id specified');
        $this->assertNotNull(Pet_fence::find($createdPet_fence['id']), 'Pet_fence with given id must be in DB');
        $this->assertModelData($petFence, $createdPet_fence);
    }

    /**
     * @test read
     */
    public function testReadPet_fence()
    {
        $petFence = $this->makePet_fence();
        $dbPet_fence = $this->petFenceRepo->find($petFence->id);
        $dbPet_fence = $dbPet_fence->toArray();
        $this->assertModelData($petFence->toArray(), $dbPet_fence);
    }

    /**
     * @test update
     */
    public function testUpdatePet_fence()
    {
        $petFence = $this->makePet_fence();
        $fakePet_fence = $this->fakePet_fenceData();
        $updatedPet_fence = $this->petFenceRepo->update($fakePet_fence, $petFence->id);
        $this->assertModelData($fakePet_fence, $updatedPet_fence->toArray());
        $dbPet_fence = $this->petFenceRepo->find($petFence->id);
        $this->assertModelData($fakePet_fence, $dbPet_fence->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletePet_fence()
    {
        $petFence = $this->makePet_fence();
        $resp = $this->petFenceRepo->delete($petFence->id);
        $this->assertTrue($resp);
        $this->assertNull(Pet_fence::find($petFence->id), 'Pet_fence should not exist in DB');
    }
}
