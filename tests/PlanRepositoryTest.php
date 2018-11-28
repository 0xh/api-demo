<?php

use App\Models\Plan;
use App\Repositories\PlanRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PlanRepositoryTest extends TestCase
{
    use MakePlanTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PlanRepository
     */
    protected $planRepo;

    public function setUp()
    {
        parent::setUp();
        $this->planRepo = App::make(PlanRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatePlan()
    {
        $plan = $this->fakePlanData();
        $createdPlan = $this->planRepo->create($plan);
        $createdPlan = $createdPlan->toArray();
        $this->assertArrayHasKey('id', $createdPlan);
        $this->assertNotNull($createdPlan['id'], 'Created Plan must have id specified');
        $this->assertNotNull(Plan::find($createdPlan['id']), 'Plan with given id must be in DB');
        $this->assertModelData($plan, $createdPlan);
    }

    /**
     * @test read
     */
    public function testReadPlan()
    {
        $plan = $this->makePlan();
        $dbPlan = $this->planRepo->find($plan->id);
        $dbPlan = $dbPlan->toArray();
        $this->assertModelData($plan->toArray(), $dbPlan);
    }

    /**
     * @test update
     */
    public function testUpdatePlan()
    {
        $plan = $this->makePlan();
        $fakePlan = $this->fakePlanData();
        $updatedPlan = $this->planRepo->update($fakePlan, $plan->id);
        $this->assertModelData($fakePlan, $updatedPlan->toArray());
        $dbPlan = $this->planRepo->find($plan->id);
        $this->assertModelData($fakePlan, $dbPlan->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletePlan()
    {
        $plan = $this->makePlan();
        $resp = $this->planRepo->delete($plan->id);
        $this->assertTrue($resp);
        $this->assertNull(Plan::find($plan->id), 'Plan should not exist in DB');
    }
}
