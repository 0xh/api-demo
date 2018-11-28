<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RollApiTest extends TestCase
{
    use MakeRollTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateRoll()
    {
        $roll = $this->fakeRollData();
        $this->json('POST', '/api/v1/rolls', $roll);

        $this->assertApiResponse($roll);
    }

    /**
     * @test
     */
    public function testReadRoll()
    {
        $roll = $this->makeRoll();
        $this->json('GET', '/api/v1/rolls/'.$roll->id);

        $this->assertApiResponse($roll->toArray());
    }

    /**
     * @test
     */
    public function testUpdateRoll()
    {
        $roll = $this->makeRoll();
        $editedRoll = $this->fakeRollData();

        $this->json('PUT', '/api/v1/rolls/'.$roll->id, $editedRoll);

        $this->assertApiResponse($editedRoll);
    }

    /**
     * @test
     */
    public function testDeleteRoll()
    {
        $roll = $this->makeRoll();
        $this->json('DELETE', '/api/v1/rolls/'.$roll->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/rolls/'.$roll->id);

        $this->assertResponseStatus(404);
    }
}
