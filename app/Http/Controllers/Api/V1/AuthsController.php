<?php
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use \Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use App\Http\Controllers\AppBaseController;
use Validator;
use Illuminate\Support\Facades\Storage;
class AuthsController extends AppBaseController
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateAnimalAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/auths/login",
     *      summary="Log in",
     *      tags={"User"},
     *      description="Log in",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="email",
     *          in="formData",
     *          required=true,
     *          type="string",
     *          description="email",
     *      ),
     *      @SWG\Parameter(
     *          name="password",
     *          in="formData",
     *          required=true,
     *          type="string",
     *          description="password",
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
     *                  ref="#/definitions/User"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->only('email', 'password'), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->getMessageBag()->toArray());
        }
        if(\Auth::attempt($request->only('email', 'password')))
        {
            $user = $this->repository->find(\Auth::user()->id);
            $user->generateAccessToken();
            $user->save();


            $avatar = $user->getAvatar();
            $data = array_add($user, 'avatar', $avatar);
            return $this->sendResponse($data, 'Login Successfully');

        }else{
            return $this->sendError('Login Failed! Email or password incorrect.');
        }

    }
}