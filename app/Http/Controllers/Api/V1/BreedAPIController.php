<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateBreedAPIRequest;
use App\Http\Requests\API\UpdateBreedAPIRequest;
use App\Models\Breed;
use App\Repositories\BreedRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class BreedController
 * @package App\Http\Controllers\Api\V1
 */

class BreedAPIController extends AppBaseController
{
    /** @var  BreedRepository */
    private $breedRepository;

    public function __construct(BreedRepository $breedRepo)
    {
        $this->breedRepository = $breedRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/breeds",
     *      summary="Get a listing of the Breeds.",
     *      tags={"Breed"},
     *      description="Get all Breeds",
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
     *                  @SWG\Items(ref="#/definitions/Breed")
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
        $this->breedRepository->pushCriteria(new RequestCriteria($request));
        $this->breedRepository->pushCriteria(new LimitOffsetCriteria($request));
        $breeds = $this->breedRepository->all();

        return $this->sendResponse($breeds->toArray(), 'Breeds retrieved successfully');
    }

    /**
     * @param CreateBreedAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/breeds",
     *      summary="Store a newly created Breed in storage",
     *      tags={"Breed"},
     *      description="Store Breed",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Breed that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Breed")
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
     *                  ref="#/definitions/Breed"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateBreedAPIRequest $request)
    {
        $input = $request->all();

        $breeds = $this->breedRepository->create($input);

        return $this->sendResponse($breeds->toArray(), 'Breed saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/breeds/{id}",
     *      summary="Display the specified Breed",
     *      tags={"Breed"},
     *      description="Get Breed",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Breed",
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
     *                  ref="#/definitions/Breed"
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
        /** @var Breed $breed */
        $breed = $this->breedRepository->findWithoutFail($id);

        if (empty($breed)) {
            return $this->sendError('Breed not found');
        }

        return $this->sendResponse($breed->toArray(), 'Breed retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateBreedAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/breeds/{id}",
     *      summary="Update the specified Breed in storage",
     *      tags={"Breed"},
     *      description="Update Breed",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Breed",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Breed that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Breed")
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
     *                  ref="#/definitions/Breed"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($uuid, UpdateBreedAPIRequest $request)
    {
        $input = $request->all();

        /** @var Breed $breed */
        $breed = Breed::uuid($uuid);

        if (empty($breed)) {
            return $this->sendError('Breed not found');
        }

        $breed = $this->breedRepository->update($input, $breed->id);

        return $this->sendResponse($breed->toArray(), 'Breed updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/breeds/{id}",
     *      summary="Remove the specified Breed from storage",
     *      tags={"Breed"},
     *      description="Delete Breed",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Breed",
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
    public function destroy($uuid)
    {
        /** @var Breed $breed */
        $breed = Breed::uuid($uuid);

        if (empty($breed)) {
            return $this->sendError('Breed not found');
        }

        $breed->delete();

        return $this->sendResponse($uuid, 'Breed deleted successfully');
    }

    public function getAllBreeds(){
        $breeds = $this->breedRepository->all();

        return $this->sendResponse($breeds->toArray(), 'Breeds retrieved successfully');
    }
}
