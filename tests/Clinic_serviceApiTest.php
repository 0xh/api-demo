<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Clinic_serviceApiTest extends TestCase
{
    use MakeClinic_serviceTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateClinic_service()
    {
        $clinicService = $this->fakeClinic_serviceData();
        $this->json('POST', '/api/v1/clinicServices', $clinicService);

        $this->assertApiResponse($clinicService);
    }

    /**
     * @test
     */
    public function testReadClinic_service()
    {
        $clinicService = $this->makeClinic_service();
        $this->json('GET', '/api/v1/clinicServices/'.$clinicService->id);

        $this->assertApiResponse($clinicService->toArray());
    }

    /**
     * @test
     */
    public function testUpdateClinic_service()
    {
        $clinicService = $this->makeClinic_service();
        $editedClinic_service = $this->fakeClinic_serviceData();

        $this->json('PUT', '/api/v1/clinicServices/'.$clinicService->id, $editedClinic_service);

        $this->assertApiResponse($editedClinic_service);
    }

    /**
     * @test
     */
    public function testDeleteClinic_service()
    {
        $clinicService = $this->makeClinic_service();
        $this->json('DELETE', '/api/v1/clinicServices/'.$clinicService->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/clinicServices/'.$clinicService->id);

        $this->assertResponseStatus(404);
    }
}
