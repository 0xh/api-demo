<?php

use App\Models\FenceData;
use App\Repositories\FenceDataRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FenceDataRepositoryTest extends TestCase
{
    use MakeFenceDataTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var FenceDataRepository
     */
    protected $fenceDataRepo;

    public function setUp()
    {
        parent::setUp();
        $this->fenceDataRepo = App::make(FenceDataRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateFenceData()
    {
        $fenceData = $this->fakeFenceDataData();
        $createdFenceData = $this->fenceDataRepo->create($fenceData);
        $createdFenceData = $createdFenceData->toArray();
        $this->assertArrayHasKey('id', $createdFenceData);
        $this->assertNotNull($createdFenceData['id'], 'Created FenceData must have id specified');
        $this->assertNotNull(FenceData::find($createdFenceData['id']), 'FenceData with given id must be in DB');
        $this->assertModelData($fenceData, $createdFenceData);
    }

    /**
     * @test read
     */
    public function testReadFenceData()
    {
        $fenceData = $this->makeFenceData();
        $dbFenceData = $this->fenceDataRepo->find($fenceData->id);
        $dbFenceData = $dbFenceData->toArray();
        $this->assertModelData($fenceData->toArray(), $dbFenceData);
    }

    /**
     * @test update
     */
    public function testUpdateFenceData()
    {
        $fenceData = $this->makeFenceData();
        $fakeFenceData = $this->fakeFenceDataData();
        $updatedFenceData = $this->fenceDataRepo->update($fakeFenceData, $fenceData->id);
        $this->assertModelData($fakeFenceData, $updatedFenceData->toArray());
        $dbFenceData = $this->fenceDataRepo->find($fenceData->id);
        $this->assertModelData($fakeFenceData, $dbFenceData->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteFenceData()
    {
        $fenceData = $this->makeFenceData();
        $resp = $this->fenceDataRepo->delete($fenceData->id);
        $this->assertTrue($resp);
        $this->assertNull(FenceData::find($fenceData->id), 'FenceData should not exist in DB');
    }
}
