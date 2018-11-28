<?php
namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use \Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use App\Http\Controllers\AppBaseController;
use App\Models\Company;

class UsersController extends AppBaseController
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register(Request $request)
    {
        try
        {   
            $company = $request->input('company');
            $user = $this->repository->create($request->all());
            $user->generateAccessToken();
            $user->save();
            if($user && $company){
                $user->companies()->create($company);
            }
            $user->companies;
            $avatar = url('/img/default/default-avatar.png');
            $data = array_add($user, 'avatar', $avatar);
            return $this->sendResponse($data, 'Register Successfully');
        }catch(ValidatorException $e)
        {
            return $this->sendError($e->getMessageBag());
        }
    }

}