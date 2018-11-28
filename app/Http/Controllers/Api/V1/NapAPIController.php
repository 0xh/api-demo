<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateNapAPIRequest;
use App\Http\Requests\API\UpdateNapAPIRequest;
use App\Models\Nap;
use App\Repositories\NapRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class NapController
 * @package App\Http\Controllers\Api\V1
 */

class NapAPIController extends AppBaseController
{
    /** @var  NapRepository */
    private $napRepository;

    public function __construct(NapRepository $napRepo)
    {
        $this->napRepository = $napRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/naps",
     *      summary="Get a listing of the Naps.",
     *      tags={"Nap"},
     *      description="Get all Naps",
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
     *                  @SWG\Items(ref="#/definitions/Nap")
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
        $this->napRepository->pushCriteria(new RequestCriteria($request));
        $this->napRepository->pushCriteria(new LimitOffsetCriteria($request));
        $naps = $this->napRepository->all();

        return $this->sendResponse($naps->toArray(), 'Naps retrieved successfully');
    }

    /**
     * @param CreateNapAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/naps",
     *      summary="Store a newly created Nap in storage",
     *      tags={"Nap"},
     *      description="Store Nap",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Nap that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Nap")
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
     *                  ref="#/definitions/Nap"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateNapAPIRequest $request)
    {
        $input = $request->all();

        $naps = $this->napRepository->create($input);

        return $this->sendResponse($naps->toArray(), 'Nap saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/naps/{id}",
     *      summary="Display the specified Nap",
     *      tags={"Nap"},
     *      description="Get Nap",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Nap",
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
     *                  ref="#/definitions/Nap"
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
        /** @var Nap $nap */
        $nap = $this->napRepository->findWithoutFail($id);

        if (empty($nap)) {
            return $this->sendError('Nap not found');
        }

        return $this->sendResponse($nap->toArray(), 'Nap retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateNapAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/naps/{id}",
     *      summary="Update the specified Nap in storage",
     *      tags={"Nap"},
     *      description="Update Nap",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Nap",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Nap that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Nap")
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
     *                  ref="#/definitions/Nap"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateNapAPIRequest $request)
    {
        $input = $request->all();

        /** @var Nap $nap */
        $nap = $this->napRepository->findWithoutFail($id);

        if (empty($nap)) {
            return $this->sendError('Nap not found');
        }

        $nap = $this->napRepository->update($input, $id);

        return $this->sendResponse($nap->toArray(), 'Nap updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/naps/{id}",
     *      summary="Remove the specified Nap from storage",
     *      tags={"Nap"},
     *      description="Delete Nap",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Nap",
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
        /** @var Nap $nap */
        $nap = $this->napRepository->findWithoutFail($id);

        if (empty($nap)) {
            return $this->sendError('Nap not found');
        }

        $nap->delete();

        return $this->sendResponse($id, 'Nap deleted successfully');
    }
}
