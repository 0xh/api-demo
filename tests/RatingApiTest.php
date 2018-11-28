<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RatingApiTest extends TestCase
{
    use MakeRatingTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateRating()
    {
        $rating = $this->fakeRatingData();
        $this->json('POST', '/api/v1/ratings', $rating);

        $this->assertApiResponse($rating);
    }

    /**
     * @test
     */
    public function testReadRating()
    {
        $rating = $this->makeRating();
        $this->json('GET', '/api/v1/ratings/'.$rating->id);

        $this->assertApiResponse($rating->toArray());
    }

    /**
     * @test
     */
    public function testUpdateRating()
    {
        $rating = $this->makeRating();
        $editedRating = $this->fakeRatingData();

        $this->json('PUT', '/api/v1/ratings/'.$rating->id, $editedRating);

        $this->assertApiResponse($editedRating);
    }

    /**
     * @test
     */
    public function testDeleteRating()
    {
        $rating = $this->makeRating();
        $this->json('DELETE', '/api/v1/ratings/'.$rating->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/ratings/'.$rating->id);

        $this->assertResponseStatus(404);
    }
}
