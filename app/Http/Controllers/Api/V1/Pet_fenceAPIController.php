<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreatePet_fenceAPIRequest;
use App\Http\Requests\API\UpdatePet_fenceAPIRequest;
use App\Models\Pet_fence;
use App\Repositories\Pet_fenceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class Pet_fenceController
 * @package App\Http\Controllers\Api\V1
 */

class Pet_fenceAPIController extends AppBaseController
{
    /** @var  Pet_fenceRepository */
    private $petFenceRepository;

    public function __construct(Pet_fenceRepository $petFenceRepo)
    {
        $this->petFenceRepository = $petFenceRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/petFences",
     *      summary="Get a listing of the Pet_fences.",
     *      tags={"Pet_fence"},
     *      description="Get all Pet_fences",
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
     *                  @SWG\Items(ref="#/definitions/Pet_fence")
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
        $this->petFenceRepository->pushCriteria(new RequestCriteria($request));
        $this->petFenceRepository->pushCriteria(new LimitOffsetCriteria($request));
        $petFences = $this->petFenceRepository->all();

        return $this->sendResponse($petFences->toArray(), 'Pet Fences retrieved successfully');
    }

    /**
     * @param CreatePet_fenceAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/petFences",
     *      summary="Store a newly created Pet_fence in storage",
     *      tags={"Pet_fence"},
     *      description="Store Pet_fence",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Pet_fence that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Pet_fence")
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
     *                  ref="#/definitions/Pet_fence"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatePet_fenceAPIRequest $request)
    {
        $input = $request->all();

        $petFences = $this->petFenceRepository->create($input);

        return $this->sendResponse($petFences->toArray(), 'Pet Fence saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/petFences/{id}",
     *      summary="Display the specified Pet_fence",
     *      tags={"Pet_fence"},
     *      description="Get Pet_fence",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Pet_fence",
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
     *                  ref="#/definitions/Pet_fence"
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
        /** @var Pet_fence $petFence */
        $petFence = $this->petFenceRepository->findWithoutFail($id);

        if (empty($petFence)) {
            return $this->sendError('Pet Fence not found');
        }

        return $this->sendResponse($petFence->toArray(), 'Pet Fence retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatePet_fenceAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/petFences/{id}",
     *      summary="Update the specified Pet_fence in storage",
     *      tags={"Pet_fence"},
     *      description="Update Pet_fence",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Pet_fence",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Pet_fence that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Pet_fence")
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
     *                  ref="#/definitions/Pet_fence"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdatePet_fenceAPIRequest $request)
    {
        $input = $request->all();

        /** @var Pet_fence $petFence */
        $petFence = $this->petFenceRepository->findWithoutFail($id);

        if (empty($petFence)) {
            return $this->sendError('Pet Fence not found');
        }

        $petFence = $this->petFenceRepository->update($input, $id);

        return $this->sendResponse($petFence->toArray(), 'Pet_fence updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/petFences/{id}",
     *      summary="Remove the specified Pet_fence from storage",
     *      tags={"Pet_fence"},
     *      description="Delete Pet_fence",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Pet_fence",
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
        /** @var Pet_fence $petFence */
        $petFence = $this->petFenceRepository->findWithoutFail($id);

        if (empty($petFence)) {
            return $this->sendError('Pet Fence not found');
        }

        $petFence->delete();

        return $this->sendResponse($id, 'Pet Fence deleted successfully');
    }
}
