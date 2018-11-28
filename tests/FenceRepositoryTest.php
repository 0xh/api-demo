<?php

use App\Models\Fence;
use App\Repositories\FenceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FenceRepositoryTest extends TestCase
{
    use MakeFenceTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var FenceRepository
     */
    protected $fenceRepo;

    public function setUp()
    {
        parent::setUp();
        $this->fenceRepo = App::make(FenceRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateFence()
    {
        $fence = $this->fakeFenceData();
        $createdFence = $this->fenceRepo->create($fence);
        $createdFence = $createdFence->toArray();
        $this->assertArrayHasKey('id', $createdFence);
        $this->assertNotNull($createdFence['id'], 'Created Fence must have id specified');
        $this->assertNotNull(Fence::find($createdFence['id']), 'Fence with given id must be in DB');
        $this->assertModelData($fence, $createdFence);
    }

    /**
     * @test read
     */
    public function testReadFence()
    {
        $fence = $this->makeFence();
        $dbFence = $this->fenceRepo->find($fence->id);
        $dbFence = $dbFence->toArray();
        $this->assertModelData($fence->toArray(), $dbFence);
    }

    /**
     * @test update
     */
    public function testUpdateFence()
    {
        $fence = $this->makeFence();
        $fakeFence = $this->fakeFenceData();
        $updatedFence = $this->fenceRepo->update($fakeFence, $fence->id);
        $this->assertModelData($fakeFence, $updatedFence->toArray());
        $dbFence = $this->fenceRepo->find($fence->id);
        $this->assertModelData($fakeFence, $dbFence->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteFence()
    {
        $fence = $this->makeFence();
        $resp = $this->fenceRepo->delete($fence->id);
        $this->assertTrue($resp);
        $this->assertNull(Fence::find($fence->id), 'Fence should not exist in DB');
    }
}
