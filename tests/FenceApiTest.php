<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FenceApiTest extends TestCase
{
    use MakeFenceTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateFence()
    {
        $fence = $this->fakeFenceData();
        $this->json('POST', '/api/v1/fences', $fence);

        $this->assertApiResponse($fence);
    }

    /**
     * @test
     */
    public function testReadFence()
    {
        $fence = $this->makeFence();
        $this->json('GET', '/api/v1/fences/'.$fence->id);

        $this->assertApiResponse($fence->toArray());
    }

    /**
     * @test
     */
    public function testUpdateFence()
    {
        $fence = $this->makeFence();
        $editedFence = $this->fakeFenceData();

        $this->json('PUT', '/api/v1/fences/'.$fence->id, $editedFence);

        $this->assertApiResponse($editedFence);
    }

    /**
     * @test
     */
    public function testDeleteFence()
    {
        $fence = $this->makeFence();
        $this->json('DELETE', '/api/v1/fences/'.$fence->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/fences/'.$fence->id);

        $this->assertResponseStatus(404);
    }
}
