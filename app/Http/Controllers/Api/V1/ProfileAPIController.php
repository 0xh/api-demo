<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateProfileAPIRequest;
use App\Http\Requests\API\UpdateProfileAPIRequest;
use App\Models\Profile;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Auth;

/**
 * Class ProfileController
 * @package App\Http\Controllers\Api\V1
 */

class ProfileAPIController extends AppBaseController
{
    /** @var  ProfileRepository */
    private $profileRepository;

    public function __construct(ProfileRepository $profileRepo)
    {
        $this->profileRepository = $profileRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/profiles",
     *      summary="Get a listing of the Profiles.",
     *      tags={"Profile"},
     *      description="Get all Profiles",
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
     *                  @SWG\Items(ref="#/definitions/Profile")
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
        $this->profileRepository->pushCriteria(new RequestCriteria($request));
        $this->profileRepository->pushCriteria(new LimitOffsetCriteria($request));
        $profiles = $this->profileRepository->all();

        return $this->sendResponse($profiles->toArray(), 'Profiles retrieved successfully');
    }

    /**
     * @param CreateProfileAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/profiles",
     *      summary="Store a newly created Profile in storage",
     *      tags={"Profile"},
     *      description="Store Profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Profile that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Profile")
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
     *                  ref="#/definitions/Profile"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateProfileAPIRequest $request)
    {
        $input = $request->all();
        
        $profiles = $this->profileRepository->create($input);

        return $this->sendResponse($profiles->toArray(), 'Profile saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/profiles/{id}",
     *      summary="Display the specified Profile",
     *      tags={"Profile"},
     *      description="Get Profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Profile",
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
     *                  ref="#/definitions/Profile"
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
        /** @var Profile $profile */
        $profile = $this->profileRepository->findByField('user_id',$id)->first();

        if (empty($profile)) {
            return $this->sendError('Profile not found');
        }

        return $this->sendResponse($profile->toArray(), 'Profile retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateProfileAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/profiles/{id}",
     *      summary="Update the specified Profile in storage",
     *      tags={"Profile"},
     *      description="Update Profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Profile",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Profile that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Profile")
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
     *                  ref="#/definitions/Profile"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($uuid, UpdateProfileAPIRequest $request)
    {
        $input = $request->all();

        /** @var Profile $profile */
        $profile = $this->profileRepository->findByField('UUID',$uuid)->first();

        if (empty($profile)) {
            return $this->sendError('Profile not found');
        }

        $profile = $this->profileRepository->update($input, $profile->id);

        return $this->sendResponse($profile->toArray(), 'Profile updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/profiles/{id}",
     *      summary="Remove the specified Profile from storage",
     *      tags={"Profile"},
     *      description="Delete Profile",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Profile",
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
        /** @var Profile $profile */
        $profile = $this->profileRepository->findWithoutFail($id);

        if (empty($profile)) {
            return $this->sendError('Profile not found');
        }

        $profile->delete();

        return $this->sendResponse($id, 'Profile deleted successfully');
    }

    public function getProfile($id){
        $profile = $this->profileRepository->getProfile($id);

        if (empty($profile)) {
            return $this->sendError('Profile not found');
        }

        return $this->sendResponse($profile->toArray(), 'Profile retrieved successfully');
    }
    public function createCreditCard(Request $request,$id){

        $input = $request->all();

        $credit = $this->profileRepository->createCreditCard($input,$id);

        if ($credit['error']) {
            return $this->sendError('Credit not found');
        }

        return $this->sendResponse($credit,'Create credit successfully ');
    }
    public function getInforCreditCard($id){
        $infor = $this->profileRepository->getInforCreditCard($id);

        if (empty($infor)) {
            return $this->sendError('Profile not found');
        }

        return $this->sendResponse($infor, 'Credit retrieved successfully');
    }
    public function getProfiles(Request $request){
        $this->profileRepository->pushCriteria(new RequestCriteria($request));

        $this->profileRepository->pushCriteria(new LimitOffsetCriteria($request));

        $profiles = $this->profileRepository->getProfiles();

        return $this->sendResponse($profiles, 'Users retrieved successfully');
    }
}
