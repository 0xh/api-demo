<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PlanApiTest extends TestCase
{
    use MakePlanTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatePlan()
    {
        $plan = $this->fakePlanData();
        $this->json('POST', '/api/v1/plans', $plan);

        $this->assertApiResponse($plan);
    }

    /**
     * @test
     */
    public function testReadPlan()
    {
        $plan = $this->makePlan();
        $this->json('GET', '/api/v1/plans/'.$plan->id);

        $this->assertApiResponse($plan->toArray());
    }

    /**
     * @test
     */
    public function testUpdatePlan()
    {
        $plan = $this->makePlan();
        $editedPlan = $this->fakePlanData();

        $this->json('PUT', '/api/v1/plans/'.$plan->id, $editedPlan);

        $this->assertApiResponse($editedPlan);
    }

    /**
     * @test
     */
    public function testDeletePlan()
    {
        $plan = $this->makePlan();
        $this->json('DELETE', '/api/v1/plans/'.$plan->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/plans/'.$plan->id);

        $this->assertResponseStatus(404);
    }
}
