<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JumpApiTest extends TestCase
{
    use MakeJumpTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateJump()
    {
        $jump = $this->fakeJumpData();
        $this->json('POST', '/api/v1/jumps', $jump);

        $this->assertApiResponse($jump);
    }

    /**
     * @test
     */
    public function testReadJump()
    {
        $jump = $this->makeJump();
        $this->json('GET', '/api/v1/jumps/'.$jump->id);

        $this->assertApiResponse($jump->toArray());
    }

    /**
     * @test
     */
    public function testUpdateJump()
    {
        $jump = $this->makeJump();
        $editedJump = $this->fakeJumpData();

        $this->json('PUT', '/api/v1/jumps/'.$jump->id, $editedJump);

        $this->assertApiResponse($editedJump);
    }

    /**
     * @test
     */
    public function testDeleteJump()
    {
        $jump = $this->makeJump();
        $this->json('DELETE', '/api/v1/jumps/'.$jump->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/jumps/'.$jump->id);

        $this->assertResponseStatus(404);
    }
}
