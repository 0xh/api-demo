<?php

use App\Models\Pet;
use App\Repositories\PetRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PetRepositoryTest extends TestCase
{
    use MakePetTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PetRepository
     */
    protected $petRepo;

    public function setUp()
    {
        parent::setUp();
        $this->petRepo = App::make(PetRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatePet()
    {
        $pet = $this->fakePetData();
        $createdPet = $this->petRepo->create($pet);
        $createdPet = $createdPet->toArray();
        $this->assertArrayHasKey('id', $createdPet);
        $this->assertNotNull($createdPet['id'], 'Created Pet must have id specified');
        $this->assertNotNull(Pet::find($createdPet['id']), 'Pet with given id must be in DB');
        $this->assertModelData($pet, $createdPet);
    }

    /**
     * @test read
     */
    public function testReadPet()
    {
        $pet = $this->makePet();
        $dbPet = $this->petRepo->find($pet->id);
        $dbPet = $dbPet->toArray();
        $this->assertModelData($pet->toArray(), $dbPet);
    }

    /**
     * @test update
     */
    public function testUpdatePet()
    {
        $pet = $this->makePet();
        $fakePet = $this->fakePetData();
        $updatedPet = $this->petRepo->update($fakePet, $pet->id);
        $this->assertModelData($fakePet, $updatedPet->toArray());
        $dbPet = $this->petRepo->find($pet->id);
        $this->assertModelData($fakePet, $dbPet->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletePet()
    {
        $pet = $this->makePet();
        $resp = $this->petRepo->delete($pet->id);
        $this->assertTrue($resp);
        $this->assertNull(Pet::find($pet->id), 'Pet should not exist in DB');
    }
}
