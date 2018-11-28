<?php

use App\Models\Clinic_rating;
use App\Repositories\Clinic_ratingRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class Clinic_ratingRepositoryTest extends TestCase
{
    use MakeClinic_ratingTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var Clinic_ratingRepository
     */
    protected $clinicRatingRepo;

    public function setUp()
    {
        parent::setUp();
        $this->clinicRatingRepo = App::make(Clinic_ratingRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateClinic_rating()
    {
        $clinicRating = $this->fakeClinic_ratingData();
        $createdClinic_rating = $this->clinicRatingRepo->create($clinicRating);
        $createdClinic_rating = $createdClinic_rating->toArray();
        $this->assertArrayHasKey('id', $createdClinic_rating);
        $this->assertNotNull($createdClinic_rating['id'], 'Created Clinic_rating must have id specified');
        $this->assertNotNull(Clinic_rating::find($createdClinic_rating['id']), 'Clinic_rating with given id must be in DB');
        $this->assertModelData($clinicRating, $createdClinic_rating);
    }

    /**
     * @test read
     */
    public function testReadClinic_rating()
    {
        $clinicRating = $this->makeClinic_rating();
        $dbClinic_rating = $this->clinicRatingRepo->find($clinicRating->id);
        $dbClinic_rating = $dbClinic_rating->toArray();
        $this->assertModelData($clinicRating->toArray(), $dbClinic_rating);
    }

    /**
     * @test update
     */
    public function testUpdateClinic_rating()
    {
        $clinicRating = $this->makeClinic_rating();
        $fakeClinic_rating = $this->fakeClinic_ratingData();
        $updatedClinic_rating = $this->clinicRatingRepo->update($fakeClinic_rating, $clinicRating->id);
        $this->assertModelData($fakeClinic_rating, $updatedClinic_rating->toArray());
        $dbClinic_rating = $this->clinicRatingRepo->find($clinicRating->id);
        $this->assertModelData($fakeClinic_rating, $dbClinic_rating->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteClinic_rating()
    {
        $clinicRating = $this->makeClinic_rating();
        $resp = $this->clinicRatingRepo->delete($clinicRating->id);
        $this->assertTrue($resp);
        $this->assertNull(Clinic_rating::find($clinicRating->id), 'Clinic_rating should not exist in DB');
    }
}
