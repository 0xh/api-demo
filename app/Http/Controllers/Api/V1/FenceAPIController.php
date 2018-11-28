<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateFenceAPIRequest;
use App\Http\Requests\API\UpdateFenceAPIRequest;
use App\Models\Fence;
use App\Repositories\FenceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class FenceController
 * @package App\Http\Controllers\Api\V1
 */

class FenceAPIController extends AppBaseController
{
    /** @var  FenceRepository */
    private $fenceRepository;

    public function __construct(FenceRepository $fenceRepo)
    {
        $this->fenceRepository = $fenceRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/fences",
     *      summary="Get a listing of the Fences.",
     *      tags={"Fence"},
     *      description="Get all Fences",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Fence")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $this->fenceRepository->pushCriteria(new RequestCriteria($request));
        $this->fenceRepository->pushCriteria(new LimitOffsetCriteria($request));
        $fences = $this->fenceRepository->all();

        return $this->sendResponse($fences->toArray(), 'Fences retrieved successfully');
    }

    /**
     * @param CreateFenceAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/fences",
     *      summary="Store a newly created Fence in storage",
     *      tags={"Fence"},
     *      description="Store Fence",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Fence that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Fence")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Fence"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateFenceAPIRequest $request)
    {
        $input = $request->all();

        $fences = $this->fenceRepository->create($input);

        return $this->sendResponse($fences->toArray(), 'Fence saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/fences/{id}",
     *      summary="Display the specified Fence",
     *      tags={"Fence"},
     *      description="Get Fence",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Fence",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Fence"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Fence $fence */
        $fence = $this->fenceRepository->findWithoutFail($id);

        if (empty($fence)) {
            return $this->sendError('Fence not found');
        }

        return $this->sendResponse($fence->toArray(), 'Fence retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFenceAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/fences/{id}",
     *      summary="Update the specified Fence in storage",
     *      tags={"Fence"},
     *      description="Update Fence",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Fence",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Fence that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Fence")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Fence"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateFenceAPIRequest $request)
    {
        $input = $request->all();

        /** @var Fence $fence */
        $fence = $this->fenceRepository->findWithoutFail($id);

        if (empty($fence)) {
            return $this->sendError('Fence not found');
        }

        $fence = $this->fenceRepository->update($input, $id);

        return $this->sendResponse($fence->toArray(), 'Fence updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/fences/{id}",
     *      summary="Remove the specified Fence from storage",
     *      tags={"Fence"},
     *      description="Delete Fence",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Fence",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Fence $fence */
        $fence = $this->fenceRepository->findWithoutFail($id);

        if (empty($fence)) {
            return $this->sendError('Fence not found');
        }

        $fence->delete();

        return $this->sendResponse($id, 'Fence deleted successfully');
    }
    public function createFence(CreateFenceAPIRequest $request){
        $input = $request->all();

        $pet_id = $request->input('pet');
        if(!$pet_id){
            $input['pet'] =null;
        }
        $fences = $this->fenceRepository->createFence($input);

        return $this->sendResponse($fences->toArray(), 'Fence saved successfully');
    }
    public function getFenceOfPet($id){
        $fence = $this->fenceRepository->getFenceOfPet($id);

        if (empty($fence)) {
            return $this->sendError('Fence not found');
        }

        return $this->sendResponse($fence->toArray(), 'Fence retrieved successfully');
    }
    public function getAllFence(){
        $fences = $this->fenceRepository->getAllFence();

        return $this->sendResponse($fences->toArray(), 'Fences retrieved successfully');
    }
    public function deleteFence($id){
        $fence = $this->fenceRepository->deleteFence($id);

        if (empty($fence)) {
            return $this->sendError('Fence not found');
        }

        return $this->sendResponse($id, 'Fence deleted successfully');
    }
    public function getFenceOfUser($id){
        $fences = $this->fenceRepository->getFenceOfUser($id);

        return $this->sendResponse($fences->toArray(), 'Fences retrieved successfully');
    }
    public function addFenceForPet(Request $request){
        $input = $request->all();

        $addFence = $this->fenceRepository->addFenceForPet($input);

        return $this->sendResponse($addFence->toArray(), 'Add Fence successfully');
    }
    public function updateFence(Request $request,$id){
        $input = $request->all();
        // dd($input);
        $pet_id = $request->input('pet_id');
        if(!$pet_id){
            $input['pet_id'] =null;
        }
        $fences = $this->fenceRepository->updateFence($input,$id);

        return $this->sendResponse($fences, 'Fence update successfully');
    }
    public function getPetFence($id){
        $fence = $this->fenceRepository->getPetFence($id);

        if (empty($fence)) {
            return $this->sendError('Fence not found');
        }

        return $this->sendResponse($fence->toArray(), 'Fence retrieved successfully');
    }

}
