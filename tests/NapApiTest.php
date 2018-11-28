<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NapApiTest extends TestCase
{
    use MakeNapTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateNap()
    {
        $nap = $this->fakeNapData();
        $this->json('POST', '/api/v1/naps', $nap);

        $this->assertApiResponse($nap);
    }

    /**
     * @test
     */
    public function testReadNap()
    {
        $nap = $this->makeNap();
        $this->json('GET', '/api/v1/naps/'.$nap->id);

        $this->assertApiResponse($nap->toArray());
    }

    /**
     * @test
     */
    public function testUpdateNap()
    {
        $nap = $this->makeNap();
        $editedNap = $this->fakeNapData();

        $this->json('PUT', '/api/v1/naps/'.$nap->id, $editedNap);

        $this->assertApiResponse($editedNap);
    }

    /**
     * @test
     */
    public function testDeleteNap()
    {
        $nap = $this->makeNap();
        $this->json('DELETE', '/api/v1/naps/'.$nap->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/naps/'.$nap->id);

        $this->assertResponseStatus(404);
    }
}
