<?php

use App\Models\Nap;
use App\Repositories\NapRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NapRepositoryTest extends TestCase
{
    use MakeNapTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var NapRepository
     */
    protected $napRepo;

    public function setUp()
    {
        parent::setUp();
        $this->napRepo = App::make(NapRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateNap()
    {
        $nap = $this->fakeNapData();
        $createdNap = $this->napRepo->create($nap);
        $createdNap = $createdNap->toArray();
        $this->assertArrayHasKey('id', $createdNap);
        $this->assertNotNull($createdNap['id'], 'Created Nap must have id specified');
        $this->assertNotNull(Nap::find($createdNap['id']), 'Nap with given id must be in DB');
        $this->assertModelData($nap, $createdNap);
    }

    /**
     * @test read
     */
    public function testReadNap()
    {
        $nap = $this->makeNap();
        $dbNap = $this->napRepo->find($nap->id);
        $dbNap = $dbNap->toArray();
        $this->assertModelData($nap->toArray(), $dbNap);
    }

    /**
     * @test update
     */
    public function testUpdateNap()
    {
        $nap = $this->makeNap();
        $fakeNap = $this->fakeNapData();
        $updatedNap = $this->napRepo->update($fakeNap, $nap->id);
        $this->assertModelData($fakeNap, $updatedNap->toArray());
        $dbNap = $this->napRepo->find($nap->id);
        $this->assertModelData($fakeNap, $dbNap->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteNap()
    {
        $nap = $this->makeNap();
        $resp = $this->napRepo->delete($nap->id);
        $this->assertTrue($resp);
        $this->assertNull(Nap::find($nap->id), 'Nap should not exist in DB');
    }
}
