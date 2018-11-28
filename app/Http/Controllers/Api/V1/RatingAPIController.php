<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateRatingAPIRequest;
use App\Http\Requests\API\UpdateRatingAPIRequest;
use App\Models\Rating;
use App\Repositories\RatingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class RatingController
 * @package App\Http\Controllers\Api\V1
 */

class RatingAPIController extends AppBaseController
{
    /** @var  RatingRepository */
    private $ratingRepository;

    public function __construct(RatingRepository $ratingRepo)
    {
        $this->ratingRepository = $ratingRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/ratings",
     *      summary="Get a listing of the Ratings.",
     *      tags={"Rating"},
     *      description="Get all Ratings",
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
     *                  @SWG\Items(ref="#/definitions/Rating")
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
        $this->ratingRepository->pushCriteria(new RequestCriteria($request));
        $this->ratingRepository->pushCriteria(new LimitOffsetCriteria($request));
        $ratings = $this->ratingRepository->all();

        return $this->sendResponse($ratings->toArray(), 'Ratings retrieved successfully');
    }

    /**
     * @param CreateRatingAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/ratings",
     *      summary="Store a newly created Rating in storage",
     *      tags={"Rating"},
     *      description="Store Rating",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Rating that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Rating")
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
     *                  ref="#/definitions/Rating"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateRatingAPIRequest $request)
    {
        $input = $request->all();

        $ratings = $this->ratingRepository->create($input);

        return $this->sendResponse($ratings->toArray(), 'Rating saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/ratings/{id}",
     *      summary="Display the specified Rating",
     *      tags={"Rating"},
     *      description="Get Rating",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Rating",
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
     *                  ref="#/definitions/Rating"
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
        /** @var Rating $rating */
        $rating = $this->ratingRepository->findWithoutFail($id);

        if (empty($rating)) {
            return $this->sendError('Rating not found');
        }

        return $this->sendResponse($rating->toArray(), 'Rating retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateRatingAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/ratings/{id}",
     *      summary="Update the specified Rating in storage",
     *      tags={"Rating"},
     *      description="Update Rating",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Rating",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Rating that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Rating")
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
     *                  ref="#/definitions/Rating"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateRatingAPIRequest $request)
    {
        $input = $request->all();

        /** @var Rating $rating */
        $rating = $this->ratingRepository->findWithoutFail($id);

        if (empty($rating)) {
            return $this->sendError('Rating not found');
        }

        $rating = $this->ratingRepository->update($input, $id);

        return $this->sendResponse($rating->toArray(), 'Rating updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/ratings/{id}",
     *      summary="Remove the specified Rating from storage",
     *      tags={"Rating"},
     *      description="Delete Rating",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Rating",
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
        /** @var Rating $rating */
        $rating = $this->ratingRepository->findWithoutFail($id);

        if (empty($rating)) {
            return $this->sendError('Rating not found');
        }

        $rating->delete();

        return $this->sendResponse($id, 'Rating deleted successfully');
    }
}
