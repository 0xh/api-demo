<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FenceDataApiTest extends TestCase
{
    use MakeFenceDataTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateFenceData()
    {
        $fenceData = $this->fakeFenceDataData();
        $this->json('POST', '/api/v1/fenceDatas', $fenceData);

        $this->assertApiResponse($fenceData);
    }

    /**
     * @test
     */
    public function testReadFenceData()
    {
        $fenceData = $this->makeFenceData();
        $this->json('GET', '/api/v1/fenceDatas/'.$fenceData->id);

        $this->assertApiResponse($fenceData->toArray());
    }

    /**
     * @test
     */
    public function testUpdateFenceData()
    {
        $fenceData = $this->makeFenceData();
        $editedFenceData = $this->fakeFenceDataData();

        $this->json('PUT', '/api/v1/fenceDatas/'.$fenceData->id, $editedFenceData);

        $this->assertApiResponse($editedFenceData);
    }

    /**
     * @test
     */
    public function testDeleteFenceData()
    {
        $fenceData = $this->makeFenceData();
        $this->json('DELETE', '/api/v1/fenceDatas/'.$fenceData->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/fenceDatas/'.$fenceData->id);

        $this->assertResponseStatus(404);
    }
}
