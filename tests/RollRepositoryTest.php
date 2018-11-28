<?php

use App\Models\Roll;
use App\Repositories\RollRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RollRepositoryTest extends TestCase
{
    use MakeRollTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var RollRepository
     */
    protected $rollRepo;

    public function setUp()
    {
        parent::setUp();
        $this->rollRepo = App::make(RollRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateRoll()
    {
        $roll = $this->fakeRollData();
        $createdRoll = $this->rollRepo->create($roll);
        $createdRoll = $createdRoll->toArray();
        $this->assertArrayHasKey('id', $createdRoll);
        $this->assertNotNull($createdRoll['id'], 'Created Roll must have id specified');
        $this->assertNotNull(Roll::find($createdRoll['id']), 'Roll with given id must be in DB');
        $this->assertModelData($roll, $createdRoll);
    }

    /**
     * @test read
     */
    public function testReadRoll()
    {
        $roll = $this->makeRoll();
        $dbRoll = $this->rollRepo->find($roll->id);
        $dbRoll = $dbRoll->toArray();
        $this->assertModelData($roll->toArray(), $dbRoll);
    }

    /**
     * @test update
     */
    public function testUpdateRoll()
    {
        $roll = $this->makeRoll();
        $fakeRoll = $this->fakeRollData();
        $updatedRoll = $this->rollRepo->update($fakeRoll, $roll->id);
        $this->assertModelData($fakeRoll, $updatedRoll->toArray());
        $dbRoll = $this->rollRepo->find($roll->id);
        $this->assertModelData($fakeRoll, $dbRoll->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteRoll()
    {
        $roll = $this->makeRoll();
        $resp = $this->rollRepo->delete($roll->id);
        $this->assertTrue($resp);
        $this->assertNull(Roll::find($roll->id), 'Roll should not exist in DB');
    }
}
