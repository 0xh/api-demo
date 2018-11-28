<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SmileApiTest extends TestCase
{
    use MakeSmileTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateSmile()
    {
        $smile = $this->fakeSmileData();
        $this->json('POST', '/api/v1/smiles', $smile);

        $this->assertApiResponse($smile);
    }

    /**
     * @test
     */
    public function testReadSmile()
    {
        $smile = $this->makeSmile();
        $this->json('GET', '/api/v1/smiles/'.$smile->id);

        $this->assertApiResponse($smile->toArray());
    }

    /**
     * @test
     */
    public function testUpdateSmile()
    {
        $smile = $this->makeSmile();
        $editedSmile = $this->fakeSmileData();

        $this->json('PUT', '/api/v1/smiles/'.$smile->id, $editedSmile);

        $this->assertApiResponse($editedSmile);
    }

    /**
     * @test
     */
    public function testDeleteSmile()
    {
        $smile = $this->makeSmile();
        $this->json('DELETE', '/api/v1/smiles/'.$smile->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/smiles/'.$smile->id);

        $this->assertResponseStatus(404);
    }
}
