<?php

use App\Models\Rating;
use App\Repositories\RatingRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RatingRepositoryTest extends TestCase
{
    use MakeRatingTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var RatingRepository
     */
    protected $ratingRepo;

    public function setUp()
    {
        parent::setUp();
        $this->ratingRepo = App::make(RatingRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateRating()
    {
        $rating = $this->fakeRatingData();
        $createdRating = $this->ratingRepo->create($rating);
        $createdRating = $createdRating->toArray();
        $this->assertArrayHasKey('id', $createdRating);
        $this->assertNotNull($createdRating['id'], 'Created Rating must have id specified');
        $this->assertNotNull(Rating::find($createdRating['id']), 'Rating with given id must be in DB');
        $this->assertModelData($rating, $createdRating);
    }

    /**
     * @test read
     */
    public function testReadRating()
    {
        $rating = $this->makeRating();
        $dbRating = $this->ratingRepo->find($rating->id);
        $dbRating = $dbRating->toArray();
        $this->assertModelData($rating->toArray(), $dbRating);
    }

    /**
     * @test update
     */
    public function testUpdateRating()
    {
        $rating = $this->makeRating();
        $fakeRating = $this->fakeRatingData();
        $updatedRating = $this->ratingRepo->update($fakeRating, $rating->id);
        $this->assertModelData($fakeRating, $updatedRating->toArray());
        $dbRating = $this->ratingRepo->find($rating->id);
        $this->assertModelData($fakeRating, $dbRating->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteRating()
    {
        $rating = $this->makeRating();
        $resp = $this->ratingRepo->delete($rating->id);
        $this->assertTrue($resp);
        $this->assertNull(Rating::find($rating->id), 'Rating should not exist in DB');
    }
}
