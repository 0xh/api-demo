<?php

use App\Models\Animal;
use App\Repositories\AnimalRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AnimalRepositoryTest extends TestCase
{
    use MakeAnimalTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var AnimalRepository
     */
    protected $animalRepo;

    public function setUp()
    {
        parent::setUp();
        $this->animalRepo = App::make(AnimalRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateAnimal()
    {
        $animal = $this->fakeAnimalData();
        $createdAnimal = $this->animalRepo->create($animal);
        $createdAnimal = $createdAnimal->toArray();
        $this->assertArrayHasKey('id', $createdAnimal);
        $this->assertNotNull($createdAnimal['id'], 'Created Animal must have id specified');
        $this->assertNotNull(Animal::find($createdAnimal['id']), 'Animal with given id must be in DB');
        $this->assertModelData($animal, $createdAnimal);
    }

    /**
     * @test read
     */
    public function testReadAnimal()
    {
        $animal = $this->makeAnimal();
        $dbAnimal = $this->animalRepo->find($animal->id);
        $dbAnimal = $dbAnimal->toArray();
        $this->assertModelData($animal->toArray(), $dbAnimal);
    }

    /**
     * @test update
     */
    public function testUpdateAnimal()
    {
        $animal = $this->makeAnimal();
        $fakeAnimal = $this->fakeAnimalData();
        $updatedAnimal = $this->animalRepo->update($fakeAnimal, $animal->id);
        $this->assertModelData($fakeAnimal, $updatedAnimal->toArray());
        $dbAnimal = $this->animalRepo->find($animal->id);
        $this->assertModelData($fakeAnimal, $dbAnimal->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteAnimal()
    {
        $animal = $this->makeAnimal();
        $resp = $this->animalRepo->delete($animal->id);
        $this->assertTrue($resp);
        $this->assertNull(Animal::find($animal->id), 'Animal should not exist in DB');
    }
}
