<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateClinic_serviceAPIRequest;
use App\Http\Requests\API\UpdateClinic_serviceAPIRequest;
use App\Models\Clinic_service;
use App\Repositories\Clinic_serviceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class Clinic_serviceController
 * @package App\Http\Controllers\Api\V1
 */

class Clinic_serviceAPIController extends AppBaseController
{
    /** @var  Clinic_serviceRepository */
    private $clinicServiceRepository;

    public function __construct(Clinic_serviceRepository $clinicServiceRepo)
    {
        $this->clinicServiceRepository = $clinicServiceRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/clinicServices",
     *      summary="Get a listing of the Clinic_services.",
     *      tags={"Clinic_service"},
     *      description="Get all Clinic_services",
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
     *                  @SWG\Items(ref="#/definitions/Clinic_service")
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
        $this->clinicServiceRepository->pushCriteria(new RequestCriteria($request));
        $this->clinicServiceRepository->pushCriteria(new LimitOffsetCriteria($request));
        $clinicServices = $this->clinicServiceRepository->all();

        return $this->sendResponse($clinicServices->toArray(), 'Clinic Services retrieved successfully');
    }

    /**
     * @param CreateClinic_serviceAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/clinicServices",
     *      summary="Store a newly created Clinic_service in storage",
     *      tags={"Clinic_service"},
     *      description="Store Clinic_service",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Clinic_service that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Clinic_service")
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
     *                  ref="#/definitions/Clinic_service"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateClinic_serviceAPIRequest $request)
    {
        $input = $request->all();

        $clinicServices = $this->clinicServiceRepository->create($input);

        return $this->sendResponse($clinicServices->toArray(), 'Clinic Service saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/clinicServices/{id}",
     *      summary="Display the specified Clinic_service",
     *      tags={"Clinic_service"},
     *      description="Get Clinic_service",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Clinic_service",
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
     *                  ref="#/definitions/Clinic_service"
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
        /** @var Clinic_service $clinicService */
        $clinicService = $this->clinicServiceRepository->findWithoutFail($id);

        if (empty($clinicService)) {
            return $this->sendError('Clinic Service not found');
        }

        return $this->sendResponse($clinicService->toArray(), 'Clinic Service retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateClinic_serviceAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/clinicServices/{id}",
     *      summary="Update the specified Clinic_service in storage",
     *      tags={"Clinic_service"},
     *      description="Update Clinic_service",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Clinic_service",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Clinic_service that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Clinic_service")
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
     *                  ref="#/definitions/Clinic_service"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateClinic_serviceAPIRequest $request)
    {
        $input = $request->all();

        /** @var Clinic_service $clinicService */
        $clinicService = $this->clinicServiceRepository->findWithoutFail($id);

        if (empty($clinicService)) {
            return $this->sendError('Clinic Service not found');
        }

        $clinicService = $this->clinicServiceRepository->update($input, $id);

        return $this->sendResponse($clinicService->toArray(), 'Clinic_service updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/clinicServices/{id}",
     *      summary="Remove the specified Clinic_service from storage",
     *      tags={"Clinic_service"},
     *      description="Delete Clinic_service",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Clinic_service",
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
        /** @var Clinic_service $clinicService */
        $clinicService = $this->clinicServiceRepository->findWithoutFail($id);

        if (empty($clinicService)) {
            return $this->sendError('Clinic Service not found');
        }

        $clinicService->delete();

        return $this->sendResponse($id, 'Clinic Service deleted successfully');
    }
}
