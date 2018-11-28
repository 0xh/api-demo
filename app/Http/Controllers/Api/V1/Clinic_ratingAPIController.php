<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateClinic_ratingAPIRequest;
use App\Http\Requests\API\UpdateClinic_ratingAPIRequest;
use App\Models\Clinic_rating;
use App\Repositories\Clinic_ratingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class Clinic_ratingController
 * @package App\Http\Controllers\Api\V1
 */

class Clinic_ratingAPIController extends AppBaseController
{
    /** @var  Clinic_ratingRepository */
    private $clinicRatingRepository;

    public function __construct(Clinic_ratingRepository $clinicRatingRepo)
    {
        $this->clinicRatingRepository = $clinicRatingRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/clinic_ratings",
     *      summary="Get a listing of the Clinic_ratings.",
     *      tags={"Clinic_rating"},
     *      description="Get all Clinic_ratings",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          type="string",
     *          description="Authorization",
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
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Clinic_rating")
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
        $this->clinicRatingRepository->pushCriteria(new RequestCriteria($request));
        $this->clinicRatingRepository->pushCriteria(new LimitOffsetCriteria($request));
        $clinicRatings = $this->clinicRatingRepository->all();

        return $this->sendResponse($clinicRatings->toArray(), 'Clinic Ratings retrieved successfully');
    }

    /**
     * @param CreateClinic_ratingAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/clinic_ratings",
     *      summary="Store a newly created Clinic_rating in storage",
     *      tags={"Clinic_rating"},
     *      description="Store Clinic_rating",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          type="string",
     *          description="Authorization",
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Clinic_rating that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Clinic_rating")
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
     *                  ref="#/definitions/Clinic_rating"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateClinic_ratingAPIRequest $request)
    {
        $input = $request->all();

        $clinicRatings = $this->clinicRatingRepository->create($input);

        return $this->sendResponse($clinicRatings->toArray(), 'Clinic Rating saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/clinic_ratings/{id}",
     *      summary="Display the specified Clinic_rating",
     *      tags={"Clinic_rating"},
     *      description="Get Clinic_rating",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          type="string",
     *          description="Authorization",
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Clinic_rating",
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
     *                  ref="#/definitions/Clinic_rating"
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
        /** @var Clinic_rating $clinicRating */
        $clinicRating = $this->clinicRatingRepository->findWithoutFail($id);

        if (empty($clinicRating)) {
            return $this->sendError('Clinic Rating not found');
        }

        return $this->sendResponse($clinicRating->toArray(), 'Clinic Rating retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateClinic_ratingAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/clinic_ratings/{id}",
     *      summary="Update the specified Clinic_rating in storage",
     *      tags={"Clinic_rating"},
     *      description="Update Clinic_rating",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          type="string",
     *          description="Authorization",
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Clinic_rating",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Clinic_rating that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Clinic_rating")
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
     *                  ref="#/definitions/Clinic_rating"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateClinic_ratingAPIRequest $request)
    {
        $input = $request->all();

        /** @var Clinic_rating $clinicRating */
        $clinicRating = $this->clinicRatingRepository->findWithoutFail($id);

        if (empty($clinicRating)) {
            return $this->sendError('Clinic Rating not found');
        }

        $clinicRating = $this->clinicRatingRepository->update($input, $id);

        return $this->sendResponse($clinicRating->toArray(), 'Clinic_rating updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/clinic_ratings/{id}",
     *      summary="Remove the specified Clinic_rating from storage",
     *      tags={"Clinic_rating"},
     *      description="Delete Clinic_rating",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          type="string",
     *          description="Authorization",
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Clinic_rating",
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
        /** @var Clinic_rating $clinicRating */
        $clinicRating = $this->clinicRatingRepository->findWithoutFail($id);

        if (empty($clinicRating)) {
            return $this->sendError('Clinic Rating not found');
        }

        $clinicRating->delete();

        return $this->sendResponse($id, 'Clinic Rating deleted successfully');
    }
}
