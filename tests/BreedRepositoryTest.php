<?php

use App\Models\Breed;
use App\Repositories\BreedRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BreedRepositoryTest extends TestCase
{
    use MakeBreedTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var BreedRepository
     */
    protected $breedRepo;

    public function setUp()
    {
        parent::setUp();
        $this->breedRepo = App::make(BreedRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateBreed()
    {
        $breed = $this->fakeBreedData();
        $createdBreed = $this->breedRepo->create($breed);
        $createdBreed = $createdBreed->toArray();
        $this->assertArrayHasKey('id', $createdBreed);
        $this->assertNotNull($createdBreed['id'], 'Created Breed must have id specified');
        $this->assertNotNull(Breed::find($createdBreed['id']), 'Breed with given id must be in DB');
        $this->assertModelData($breed, $createdBreed);
    }

    /**
     * @test read
     */
    public function testReadBreed()
    {
        $breed = $this->makeBreed();
        $dbBreed = $this->breedRepo->find($breed->id);
        $dbBreed = $dbBreed->toArray();
        $this->assertModelData($breed->toArray(), $dbBreed);
    }

    /**
     * @test update
     */
    public function testUpdateBreed()
    {
        $breed = $this->makeBreed();
        $fakeBreed = $this->fakeBreedData();
        $updatedBreed = $this->breedRepo->update($fakeBreed, $breed->id);
        $this->assertModelData($fakeBreed, $updatedBreed->toArray());
        $dbBreed = $this->breedRepo->find($breed->id);
        $this->assertModelData($fakeBreed, $dbBreed->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteBreed()
    {
        $breed = $this->makeBreed();
        $resp = $this->breedRepo->delete($breed->id);
        $this->assertTrue($resp);
        $this->assertNull(Breed::find($breed->id), 'Breed should not exist in DB');
    }
}
