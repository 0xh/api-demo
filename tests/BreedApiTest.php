<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BreedApiTest extends TestCase
{
    use MakeBreedTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateBreed()
    {
        $breed = $this->fakeBreedData();
        $this->json('POST', '/api/v1/breeds', $breed);

        $this->assertApiResponse($breed);
    }

    /**
     * @test
     */
    public function testReadBreed()
    {
        $breed = $this->makeBreed();
        $this->json('GET', '/api/v1/breeds/'.$breed->id);

        $this->assertApiResponse($breed->toArray());
    }

    /**
     * @test
     */
    public function testUpdateBreed()
    {
        $breed = $this->makeBreed();
        $editedBreed = $this->fakeBreedData();

        $this->json('PUT', '/api/v1/breeds/'.$breed->id, $editedBreed);

        $this->assertApiResponse($editedBreed);
    }

    /**
     * @test
     */
    public function testDeleteBreed()
    {
        $breed = $this->makeBreed();
        $this->json('DELETE', '/api/v1/breeds/'.$breed->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/breeds/'.$breed->id);

        $this->assertResponseStatus(404);
    }
}
