<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateJumpAPIRequest;
use App\Http\Requests\API\UpdateJumpAPIRequest;
use App\Models\Jump;
use App\Repositories\JumpRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class JumpController
 * @package App\Http\Controllers\Api\V1
 */

class JumpAPIController extends AppBaseController
{
    /** @var  JumpRepository */
    private $jumpRepository;

    public function __construct(JumpRepository $jumpRepo)
    {
        $this->jumpRepository = $jumpRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/jumps",
     *      summary="Get a listing of the Jumps.",
     *      tags={"Jump"},
     *      description="Get all Jumps",
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
     *                  @SWG\Items(ref="#/definitions/Jump")
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
        $this->jumpRepository->pushCriteria(new RequestCriteria($request));
        $this->jumpRepository->pushCriteria(new LimitOffsetCriteria($request));
        $jumps = $this->jumpRepository->all();

        return $this->sendResponse($jumps->toArray(), 'Jumps retrieved successfully');
    }

    /**
     * @param CreateJumpAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/jumps",
     *      summary="Store a newly created Jump in storage",
     *      tags={"Jump"},
     *      description="Store Jump",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Jump that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Jump")
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
     *                  ref="#/definitions/Jump"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateJumpAPIRequest $request)
    {
        $input = $request->all();

        $jumps = $this->jumpRepository->create($input);

        return $this->sendResponse($jumps->toArray(), 'Jump saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/jumps/{id}",
     *      summary="Display the specified Jump",
     *      tags={"Jump"},
     *      description="Get Jump",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Jump",
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
     *                  ref="#/definitions/Jump"
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
        /** @var Jump $jump */
        $jump = $this->jumpRepository->findWithoutFail($id);

        if (empty($jump)) {
            return $this->sendError('Jump not found');
        }

        return $this->sendResponse($jump->toArray(), 'Jump retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateJumpAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/jumps/{id}",
     *      summary="Update the specified Jump in storage",
     *      tags={"Jump"},
     *      description="Update Jump",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Jump",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Jump that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Jump")
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
     *                  ref="#/definitions/Jump"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateJumpAPIRequest $request)
    {
        $input = $request->all();

        /** @var Jump $jump */
        $jump = $this->jumpRepository->findWithoutFail($id);

        if (empty($jump)) {
            return $this->sendError('Jump not found');
        }

        $jump = $this->jumpRepository->update($input, $id);

        return $this->sendResponse($jump->toArray(), 'Jump updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/jumps/{id}",
     *      summary="Remove the specified Jump from storage",
     *      tags={"Jump"},
     *      description="Delete Jump",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Jump",
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
        /** @var Jump $jump */
        $jump = $this->jumpRepository->findWithoutFail($id);

        if (empty($jump)) {
            return $this->sendError('Jump not found');
        }

        $jump->delete();

        return $this->sendResponse($id, 'Jump deleted successfully');
    }
}
