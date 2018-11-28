<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AnimalApiTest extends TestCase
{
    use MakeAnimalTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateAnimal()
    {
        $animal = $this->fakeAnimalData();
        $this->json('POST', '/api/v1/animals', $animal);

        $this->assertApiResponse($animal);
    }

    /**
     * @test
     */
    public function testReadAnimal()
    {
        $animal = $this->makeAnimal();
        $this->json('GET', '/api/v1/animals/'.$animal->id);

        $this->assertApiResponse($animal->toArray());
    }

    /**
     * @test
     */
    public function testUpdateAnimal()
    {
        $animal = $this->makeAnimal();
        $editedAnimal = $this->fakeAnimalData();

        $this->json('PUT', '/api/v1/animals/'.$animal->id, $editedAnimal);

        $this->assertApiResponse($editedAnimal);
    }

    /**
     * @test
     */
    public function testDeleteAnimal()
    {
        $animal = $this->makeAnimal();
        $this->json('DELETE', '/api/v1/animals/'.$animal->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/animals/'.$animal->id);

        $this->assertResponseStatus(404);
    }
}
