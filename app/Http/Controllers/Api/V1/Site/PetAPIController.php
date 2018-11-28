<?php

namespace App\Http\Controllers\Api\V1\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PetRepository;
use App\Transformers\Api\V1\Site\PetTransformer;
use App\Models\Pet;
use Response;

class PetAPIController extends AppBaseController
{
	/** @var  PetRepository */
    private $petRepository;

    public function __construct(PetRepository $petRepo)
    {
        $this->petRepository = $petRepo;
    }

    public function getListPetsOfUser($userId)
    {

        $pets = $this->petRepository->getListPetsOfUser($userId);


        return $this->respondWithCollection($pets);
    }

    public function getListPetsOfUserNofence($userId)
    {

    	$pets = $this->petRepository->getListPetsOfUserNofence($userId);


    	return $this->respondWithCollection($pets);
    }

    public function getPetLocationPaginate(Request $request, $userId, $per_page = 10){

        $pets = $this->petRepository->getPetLocationPaginate($userId, $per_page);

        return $this->respondWithPaginator($pets, new PetTransformer());
    }
}
