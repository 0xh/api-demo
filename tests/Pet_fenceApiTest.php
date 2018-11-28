<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Pet_fenceApiTest extends TestCase
{
    use MakePet_fenceTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatePet_fence()
    {
        $petFence = $this->fakePet_fenceData();
        $this->json('POST', '/api/v1/petFences', $petFence);

        $this->assertApiResponse($petFence);
    }

    /**
     * @test
     */
    public function testReadPet_fence()
    {
        $petFence = $this->makePet_fence();
        $this->json('GET', '/api/v1/petFences/'.$petFence->id);

        $this->assertApiResponse($petFence->toArray());
    }

    /**
     * @test
     */
    public function testUpdatePet_fence()
    {
        $petFence = $this->makePet_fence();
        $editedPet_fence = $this->fakePet_fenceData();

        $this->json('PUT', '/api/v1/petFences/'.$petFence->id, $editedPet_fence);

        $this->assertApiResponse($editedPet_fence);
    }

    /**
     * @test
     */
    public function testDeletePet_fence()
    {
        $petFence = $this->makePet_fence();
        $this->json('DELETE', '/api/v1/petFences/'.$petFence->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/petFences/'.$petFence->id);

        $this->assertResponseStatus(404);
    }
}
