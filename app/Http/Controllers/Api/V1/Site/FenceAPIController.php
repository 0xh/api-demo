<?php

namespace App\Http\Controllers\Api\V1\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Repositories\FenceRepository;
use App\Http\Requests\Api\V1\Site\CreateFenceAPIRequest;
use App\Transformers\Api\V1\Site\FenceTransformer;
use App\Models\Fence;
use Response;

class FenceAPIController extends AppBaseController
{
	/** @var  FenceRepository */
    private $fenceRepository;

    public function __construct(FenceRepository $fenceRepo)
    {
        $this->fenceRepository = $fenceRepo;
    }

    public function getFence($id)
    {

    	$fence = $this->fenceRepository->getFence($id);

        if (empty($fence)) {
            return $this->errorNotFound('Fence not found');
        }

    	return $this->respondWithItem($fence, new FenceTransformer());
    }

    public function getFenceOfUser($id){
        $fences = $this->fenceRepository->getFenceOfUser($id);

        return $this->respondWithCollection($fences, new FenceTransformer());
    }

    public function createFence(CreateFenceAPIRequest $request){

        $fence = $this->fenceRepository->createFence($request->all());

        return $this->respondWithNewItem($fence);
    }

    public function updateFence(Request $request, $id){

        $fence = $this->fenceRepository->updateFence($request->all(), $id);

        return $this->respondWithItem($fence);
    }

    public function deleteFence($id){
        $fence = $this->fenceRepository->deleteFence($id);

        if (empty($fence)) {
            return $this->sendError('Fence not found');
        }

        return $this->sendResponse($fence, 'Fence deleted successfully');
    }

    public function getFenceOfPet($id){
        $fence = $this->fenceRepository->getFenceOfPet($id);

        if (empty($fence)) {
            return $this->sendError('Fence not found');
        }

        return $this->sendResponse($fence->toArray(), 'Fence retrieved successfully');
    }
}
