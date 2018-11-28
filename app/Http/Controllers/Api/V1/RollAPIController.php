<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateRollAPIRequest;
use App\Http\Requests\API\UpdateRollAPIRequest;
use App\Models\Roll;
use App\Repositories\RollRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class RollController
 * @package App\Http\Controllers\Api\V1
 */

class RollAPIController extends AppBaseController
{
    /** @var  RollRepository */
    private $rollRepository;

    public function __construct(RollRepository $rollRepo)
    {
        $this->rollRepository = $rollRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/rolls",
     *      summary="Get a listing of the Rolls.",
     *      tags={"Roll"},
     *      description="Get all Rolls",
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
     *                  @SWG\Items(ref="#/definitions/Roll")
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
        $this->rollRepository->pushCriteria(new RequestCriteria($request));
        $this->rollRepository->pushCriteria(new LimitOffsetCriteria($request));
        $rolls = $this->rollRepository->all();

        return $this->sendResponse($rolls->toArray(), 'Rolls retrieved successfully');
    }

    /**
     * @param CreateRollAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/rolls",
     *      summary="Store a newly created Roll in storage",
     *      tags={"Roll"},
     *      description="Store Roll",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Roll that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Roll")
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
     *                  ref="#/definitions/Roll"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateRollAPIRequest $request)
    {
        $input = $request->all();

        $rolls = $this->rollRepository->create($input);

        return $this->sendResponse($rolls->toArray(), 'Roll saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/rolls/{id}",
     *      summary="Display the specified Roll",
     *      tags={"Roll"},
     *      description="Get Roll",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Roll",
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
     *                  ref="#/definitions/Roll"
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
        /** @var Roll $roll */
        $roll = $this->rollRepository->findWithoutFail($id);

        if (empty($roll)) {
            return $this->sendError('Roll not found');
        }

        return $this->sendResponse($roll->toArray(), 'Roll retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateRollAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/rolls/{id}",
     *      summary="Update the specified Roll in storage",
     *      tags={"Roll"},
     *      description="Update Roll",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Roll",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Roll that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Roll")
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
     *                  ref="#/definitions/Roll"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateRollAPIRequest $request)
    {
        $input = $request->all();

        /** @var Roll $roll */
        $roll = $this->rollRepository->findWithoutFail($id);

        if (empty($roll)) {
            return $this->sendError('Roll not found');
        }

        $roll = $this->rollRepository->update($input, $id);

        return $this->sendResponse($roll->toArray(), 'Roll updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/rolls/{id}",
     *      summary="Remove the specified Roll from storage",
     *      tags={"Roll"},
     *      description="Delete Roll",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Roll",
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
        /** @var Roll $roll */
        $roll = $this->rollRepository->findWithoutFail($id);

        if (empty($roll)) {
            return $this->sendError('Roll not found');
        }

        $roll->delete();

        return $this->sendResponse($id, 'Roll deleted successfully');
    }
}
