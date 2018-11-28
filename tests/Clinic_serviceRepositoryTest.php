<?php

use App\Models\Clinic_service;
use App\Repositories\Clinic_serviceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Clinic_serviceRepositoryTest extends TestCase
{
    use MakeClinic_serviceTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var Clinic_serviceRepository
     */
    protected $clinicServiceRepo;

    public function setUp()
    {
        parent::setUp();
        $this->clinicServiceRepo = App::make(Clinic_serviceRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateClinic_service()
    {
        $clinicService = $this->fakeClinic_serviceData();
        $createdClinic_service = $this->clinicServiceRepo->create($clinicService);
        $createdClinic_service = $createdClinic_service->toArray();
        $this->assertArrayHasKey('id', $createdClinic_service);
        $this->assertNotNull($createdClinic_service['id'], 'Created Clinic_service must have id specified');
        $this->assertNotNull(Clinic_service::find($createdClinic_service['id']), 'Clinic_service with given id must be in DB');
        $this->assertModelData($clinicService, $createdClinic_service);
    }

    /**
     * @test read
     */
    public function testReadClinic_service()
    {
        $clinicService = $this->makeClinic_service();
        $dbClinic_service = $this->clinicServiceRepo->find($clinicService->id);
        $dbClinic_service = $dbClinic_service->toArray();
        $this->assertModelData($clinicService->toArray(), $dbClinic_service);
    }

    /**
     * @test update
     */
    public function testUpdateClinic_service()
    {
        $clinicService = $this->makeClinic_service();
        $fakeClinic_service = $this->fakeClinic_serviceData();
        $updatedClinic_service = $this->clinicServiceRepo->update($fakeClinic_service, $clinicService->id);
        $this->assertModelData($fakeClinic_service, $updatedClinic_service->toArray());
        $dbClinic_service = $this->clinicServiceRepo->find($clinicService->id);
        $this->assertModelData($fakeClinic_service, $dbClinic_service->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteClinic_service()
    {
        $clinicService = $this->makeClinic_service();
        $resp = $this->clinicServiceRepo->delete($clinicService->id);
        $this->assertTrue($resp);
        $this->assertNull(Clinic_service::find($clinicService->id), 'Clinic_service should not exist in DB');
    }
}
