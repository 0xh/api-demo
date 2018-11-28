<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Clinic_ratingApiTest extends TestCase
{
    use MakeClinic_ratingTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateClinic_rating()
    {
        $clinicRating = $this->fakeClinic_ratingData();
        $this->json('POST', '/api/v1/clinicRatings', $clinicRating);

        $this->assertApiResponse($clinicRating);
    }

    /**
     * @test
     */
    public function testReadClinic_rating()
    {
        $clinicRating = $this->makeClinic_rating();
        $this->json('GET', '/api/v1/clinicRatings/'.$clinicRating->id);

        $this->assertApiResponse($clinicRating->toArray());
    }

    /**
     * @test
     */
    public function testUpdateClinic_rating()
    {
        $clinicRating = $this->makeClinic_rating();
        $editedClinic_rating = $this->fakeClinic_ratingData();

        $this->json('PUT', '/api/v1/clinicRatings/'.$clinicRating->id, $editedClinic_rating);

        $this->assertApiResponse($editedClinic_rating);
    }

    /**
     * @test
     */
    public function testDeleteClinic_rating()
    {
        $clinicRating = $this->makeClinic_rating();
        $this->json('DELETE', '/api/v1/clinicRatings/'.$clinicRating->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/clinicRatings/'.$clinicRating->id);

        $this->assertResponseStatus(404);
    }
}
