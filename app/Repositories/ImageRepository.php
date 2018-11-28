<?php

namespace App\Repositories;

use App\Models\Image;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\Storage;

class ImageRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'url',
        'pet_id',
        'user_id',
        'UUID'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Image::class;
    }
    public function upload(array $attributes )
    {

        $user_id    = $attributes['user_id'];
        $pet_id     = $attributes['pet_id'];
        $ext        = $attributes['avatar']->guessClientExtension();
        $reName     = time();
        if(!$pet_id){
            $path   = $attributes['avatar']->storeAs('avatars/users/'.$user_id,$reName.'.'.$ext);
        }else{
            $path   = $attributes['avatar']->storeAs('avatars/pets/'.$pet_id,$reName.'.'.$ext);
        }

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);
        $model      = parent::create($attributes);
        $model      = $this->updateRelations($model, $attributes);
        $url        = $reName.'.'.$ext;
        $model->url = $url;
        $model->save();
        return $this->parserResult($model);
    }
    public function uploadFiles(array $attributes){

        $user_id    = $attributes['user_id'];
        $pet_id     = $attributes['pet_id'];
        $ext        = $attributes['avatar']->guessClientExtension();
        $reName     = time();
        if(!$pet_id){
            $path   = $attributes['avatar']->storeAs('users/'.$user_id,$reName.'.'.$ext);
        }else{
            $path   = $attributes['avatar']->storeAs('pets/'.$pet_id,$reName.'.'.$ext);
        }

        $attributes['url'] = $reName.'.'.$ext;
        $attributes['is_avatar'] = 1;
        $model      = parent::create($attributes);
        
        $imageUrl = url('storage/' . $path);

        return $imageUrl;
    }
    public function getUrlImage($id){
        $image = Image::find($id);
        $image['urlImage'] = $image->urlImage();
        return $image;
    }
}
