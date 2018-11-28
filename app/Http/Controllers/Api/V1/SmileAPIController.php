<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateSmileAPIRequest;
use App\Http\Requests\API\UpdateSmileAPIRequest;
use App\Models\Smile;
use App\Repositories\SmileRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class SmileController
 * @package App\Http\Controllers\Api\V1
 */

class SmileAPIController extends AppBaseController
{
    /** @var  SmileRepository */
    private $smileRepository;

    public function __construct(SmileRepository $smileRepo)
    {
        $this->smileRepository = $smileRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/smiles",
     *      summary="Get a listing of the Smiles.",
     *      tags={"Smile"},
     *      description="Get all Smiles",
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
     *                  @SWG\Items(ref="#/definitions/Smile")
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
        $this->smileRepository->pushCriteria(new RequestCriteria($request));
        $this->smileRepository->pushCriteria(new LimitOffsetCriteria($request));
        $smiles = $this->smileRepository->all();

        return $this->sendResponse($smiles->toArray(), 'Smiles retrieved successfully');
    }

    /**
     * @param CreateSmileAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/smiles",
     *      summary="Store a newly created Smile in storage",
     *      tags={"Smile"},
     *      description="Store Smile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Smile that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Smile")
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
     *                  ref="#/definitions/Smile"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateSmileAPIRequest $request)
    {
        $input = $request->all();

        $smiles = $this->smileRepository->create($input);

        return $this->sendResponse($smiles->toArray(), 'Smile saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/smiles/{id}",
     *      summary="Display the specified Smile",
     *      tags={"Smile"},
     *      description="Get Smile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Smile",
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
     *                  ref="#/definitions/Smile"
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
        /** @var Smile $smile */
        $smile = $this->smileRepository->findWithoutFail($id);

        if (empty($smile)) {
            return $this->sendError('Smile not found');
        }

        return $this->sendResponse($smile->toArray(), 'Smile retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateSmileAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/smiles/{id}",
     *      summary="Update the specified Smile in storage",
     *      tags={"Smile"},
     *      description="Update Smile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Smile",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Smile that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Smile")
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
     *                  ref="#/definitions/Smile"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateSmileAPIRequest $request)
    {
        $input = $request->all();

        /** @var Smile $smile */
        $smile = $this->smileRepository->findWithoutFail($id);

        if (empty($smile)) {
            return $this->sendError('Smile not found');
        }

        $smile = $this->smileRepository->update($input, $id);

        return $this->sendResponse($smile->toArray(), 'Smile updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/smiles/{id}",
     *      summary="Remove the specified Smile from storage",
     *      tags={"Smile"},
     *      description="Delete Smile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Smile",
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
        /** @var Smile $smile */
        $smile = $this->smileRepository->findWithoutFail($id);

        if (empty($smile)) {
            return $this->sendError('Smile not found');
        }

        $smile->delete();

        return $this->sendResponse($id, 'Smile deleted successfully');
    }
}
