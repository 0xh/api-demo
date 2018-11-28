<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PetApiTest extends TestCase
{
    use MakePetTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatePet()
    {
        $pet = $this->fakePetData();
        $this->json('POST', '/api/v1/pets', $pet);

        $this->assertApiResponse($pet);
    }

    /**
     * @test
     */
    public function testReadPet()
    {
        $pet = $this->makePet();
        $this->json('GET', '/api/v1/pets/'.$pet->id);

        $this->assertApiResponse($pet->toArray());
    }

    /**
     * @test
     */
    public function testUpdatePet()
    {
        $pet = $this->makePet();
        $editedPet = $this->fakePetData();

        $this->json('PUT', '/api/v1/pets/'.$pet->id, $editedPet);

        $this->assertApiResponse($editedPet);
    }

    /**
     * @test
     */
    public function testDeletePet()
    {
        $pet = $this->makePet();
        $this->json('DELETE', '/api/v1/pets/'.$pet->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/pets/'.$pet->id);

        $this->assertResponseStatus(404);
    }
}
