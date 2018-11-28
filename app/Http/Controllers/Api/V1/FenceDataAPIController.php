<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateFenceDataAPIRequest;
use App\Http\Requests\API\UpdateFenceDataAPIRequest;
use App\Models\FenceData;
use App\Repositories\FenceDataRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class FenceDataController
 * @package App\Http\Controllers\Api\V1
 */

class FenceDataAPIController extends AppBaseController
{
    /** @var  FenceDataRepository */
    private $fenceDataRepository;

    public function __construct(FenceDataRepository $fenceDataRepo)
    {
        $this->fenceDataRepository = $fenceDataRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/fenceDatas",
     *      summary="Get a listing of the FenceDatas.",
     *      tags={"FenceData"},
     *      description="Get all FenceDatas",
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
     *                  @SWG\Items(ref="#/definitions/FenceData")
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
        $this->fenceDataRepository->pushCriteria(new RequestCriteria($request));
        $this->fenceDataRepository->pushCriteria(new LimitOffsetCriteria($request));
        $fenceDatas = $this->fenceDataRepository->all();

        return $this->sendResponse($fenceDatas->toArray(), 'Fence Datas retrieved successfully');
    }

    /**
     * @param CreateFenceDataAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/fenceDatas",
     *      summary="Store a newly created FenceData in storage",
     *      tags={"FenceData"},
     *      description="Store FenceData",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FenceData that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FenceData")
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
     *                  ref="#/definitions/FenceData"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateFenceDataAPIRequest $request)
    {
        $input = $request->all();

        $fenceDatas = $this->fenceDataRepository->create($input);

        return $this->sendResponse($fenceDatas->toArray(), 'Fence Data saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/fenceDatas/{id}",
     *      summary="Display the specified FenceData",
     *      tags={"FenceData"},
     *      description="Get FenceData",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FenceData",
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
     *                  ref="#/definitions/FenceData"
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
        /** @var FenceData $fenceData */
        $fenceData = $this->fenceDataRepository->findWithoutFail($id);

        if (empty($fenceData)) {
            return $this->sendError('Fence Data not found');
        }

        return $this->sendResponse($fenceData->toArray(), 'Fence Data retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateFenceDataAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/fenceDatas/{id}",
     *      summary="Update the specified FenceData in storage",
     *      tags={"FenceData"},
     *      description="Update FenceData",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FenceData",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="FenceData that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/FenceData")
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
     *                  ref="#/definitions/FenceData"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateFenceDataAPIRequest $request)
    {
        $input = $request->all();

        /** @var FenceData $fenceData */
        $fenceData = $this->fenceDataRepository->findWithoutFail($id);

        if (empty($fenceData)) {
            return $this->sendError('Fence Data not found');
        }

        $fenceData = $this->fenceDataRepository->update($input, $id);

        return $this->sendResponse($fenceData->toArray(), 'FenceData updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/fenceDatas/{id}",
     *      summary="Remove the specified FenceData from storage",
     *      tags={"FenceData"},
     *      description="Delete FenceData",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of FenceData",
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
        /** @var FenceData $fenceData */
        $fenceData = $this->fenceDataRepository->findWithoutFail($id);

        if (empty($fenceData)) {
            return $this->sendError('Fence Data not found');
        }

        $fenceData->delete();

        return $this->sendResponse($id, 'Fence Data deleted successfully');
    }
}
