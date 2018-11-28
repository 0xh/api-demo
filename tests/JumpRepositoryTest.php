<?php

use App\Models\Jump;
use App\Repositories\JumpRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JumpRepositoryTest extends TestCase
{
    use MakeJumpTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var JumpRepository
     */
    protected $jumpRepo;

    public function setUp()
    {
        parent::setUp();
        $this->jumpRepo = App::make(JumpRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateJump()
    {
        $jump = $this->fakeJumpData();
        $createdJump = $this->jumpRepo->create($jump);
        $createdJump = $createdJump->toArray();
        $this->assertArrayHasKey('id', $createdJump);
        $this->assertNotNull($createdJump['id'], 'Created Jump must have id specified');
        $this->assertNotNull(Jump::find($createdJump['id']), 'Jump with given id must be in DB');
        $this->assertModelData($jump, $createdJump);
    }

    /**
     * @test read
     */
    public function testReadJump()
    {
        $jump = $this->makeJump();
        $dbJump = $this->jumpRepo->find($jump->id);
        $dbJump = $dbJump->toArray();
        $this->assertModelData($jump->toArray(), $dbJump);
    }

    /**
     * @test update
     */
    public function testUpdateJump()
    {
        $jump = $this->makeJump();
        $fakeJump = $this->fakeJumpData();
        $updatedJump = $this->jumpRepo->update($fakeJump, $jump->id);
        $this->assertModelData($fakeJump, $updatedJump->toArray());
        $dbJump = $this->jumpRepo->find($jump->id);
        $this->assertModelData($fakeJump, $dbJump->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteJump()
    {
        $jump = $this->makeJump();
        $resp = $this->jumpRepo->delete($jump->id);
        $this->assertTrue($resp);
        $this->assertNull(Jump::find($jump->id), 'Jump should not exist in DB');
    }
}
