<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Profile;
use InfyOm\Generator\Common\BaseRepository;
use Prettus\Repository\Events\RepositoryEntityCreated;
use \Prettus\Validator\Contracts\ValidatorInterface;
use Illuminate\Support\Facades\Storage;

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'email',
        'password',
        'token_id',
        'company_id',
        'UUID',
        'stripe_id',
        'card_brand',
        'card_last_four',
        'trail_ends_at'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    public function  validator()
    {
        return \App\Validators\UserValidator::class;
    }

    public function create(array $attributes, $rule = ValidatorInterface::RULE_CREATE)
    {
        if (!is_null($this->validator)) {
            // $attributes = $this->model->newInstance()->forceFill($attributes)->toArray();
            $this->validator->with($attributes)->passesOrFail($rule);
        }

        $model = $this->model->newInstance($attributes);
        $model->password = bcrypt($model->password);
        $model->generateAccessToken();
        $model->save();
        $this->resetModel();
        event(new RepositoryEntityCreated($this, $model));
        return $this->parserResult($model);
    }

    public function getCompanyRatings($id){
        $user = User::where('id',$id)->first();
        $company = $user->company;
        $data = [];
        if(!is_null($company)){
        	$ratingsData = \App\Models\Rating::with(['user' => function($query){
        		$query->join('profiles', 'users.id','=', 'profiles.user_id')->select('users.id', 'users.email', 'profiles.name')->get();
        	}])->where('company_id', $company->id)->get()->toArray();

        	$myCompany = \App\Models\Company::with(['user' => function($query){
        		$query->select('id', 'email');
        	}])->with(['country'=>function($query){
                $query->select('id','name');
            }])->with(['city'=>function($query){
                $query->select('id','name');
            }])->where('id', $company->id)->get();
        	$data = array_add($data, 'ratings', $ratingsData);
        	$data = array_add($data, 'company', $myCompany);
        }

        return $data;
    }

    public function getUsers(){
        $users = User::orderBy('id','DESC')->with('profile','company')->get();
        $arrUsers = $users->toArray();
        foreach($users as $key=>$user){
            $avatar = $user->getAvatar();
            $arrUsers[$key]['avatar'] = $avatar;
        }
        return $arrUsers;
    }

    public function getUserAvatar($id){
        $user = User::select('id')->find($id);
        $image = \App\Models\Image::where('user_id',$user['id'])->where('is_avatar',1)->first();
            if($image){

                if(Storage::disk('public')->exists('users/'.$user->id.'/'.$image->url)){
                    $url = url('/storage/users/'.$user['id'] .'/'.$image->url);

                }else{
                    $url = url('/storage/default/no-image.png');
                }
                $user['avatar'] = $url;
            }
        return $user;
    }


    public function updateUser (array $attributes,$id){
        $user = User::find($id);
        $user->email= $attributes['email'];
        if($attributes['password']){
            $user->password = bcrypt($attributes['password']);
        }
        $user->save();
        return $user;
    }

    public function updateUserProfile(array $attributes,$id){

        $user = User::find($id);
        $user->email= $attributes['email'];
        if($attributes['password']){
            $user->password = bcrypt($attributes['password']);
        }
        $user->save();

        if($user->profile){
            $user->profile->update($attributes['profile']);
            $profile = $user->profile;
        }else{
            if(count($attributes['profile'])){
                $attributes['profile']['user_id'] = $user->id;
                $profile = \App\Models\Profile::create($attributes['profile']);
                $profile = $profile->toArray();
            }else{
                $profile = null;
            }
        }

        $data = array_add($user->toArray(), 'avatar', $user->getAvatar());
        $data = array_add($data, 'profile', $profile);

        return $data;
    }
    public function showUser($id){
        $user = User::find($id);
        $user['avatar'] = $user->getAvatar();
        return $user;
    }
}
