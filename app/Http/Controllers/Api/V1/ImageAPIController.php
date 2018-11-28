<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\API\CreateImageAPIRequest;
use App\Http\Requests\API\UpdateImageAPIRequest;
use App\Models\Image;
use App\Repositories\ImageRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Support\Facades\Storage;
/**
 * Class ImageController
 * @package App\Http\Controllers\Api\V1
 */

class ImageAPIController extends AppBaseController
{
    /** @var  ImageRepository */
    private $imageRepository;

    public function __construct(ImageRepository $imageRepo)
    {
        $this->imageRepository = $imageRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/images",
     *      summary="Get a listing of the Images.",
     *      tags={"Image"},
     *      description="Get all Images",
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
     *                  @SWG\Items(ref="#/definitions/Image")
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
        $this->imageRepository->pushCriteria(new RequestCriteria($request));
        $this->imageRepository->pushCriteria(new LimitOffsetCriteria($request));
        $images = $this->imageRepository->all();

        return $this->sendResponse($images->toArray(), 'Images retrieved successfully');
    }

    /**
     * @param CreateImageAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/images",
     *      summary="Store a newly created Image in storage",
     *      tags={"Image"},
     *      description="Store Image",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Image that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Image")
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
     *                  ref="#/definitions/Image"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateImageAPIRequest $request)
    {
        $input = $request->all();

        $images = $this->imageRepository->create($input);

        return $this->sendResponse($images->toArray(), 'Image saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/images/{id}",
     *      summary="Display the specified Image",
     *      tags={"Image"},
     *      description="Get Image",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Image",
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
     *                  ref="#/definitions/Image"
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
        /** @var Image $image */
        $image = $this->imageRepository->findWithoutFail($id);

        if (empty($image)) {
            return $this->sendError('Image not found');
        }

        return $this->sendResponse($image->toArray(), 'Image retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateImageAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/images/{id}",
     *      summary="Update the specified Image in storage",
     *      tags={"Image"},
     *      description="Update Image",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Image",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Image that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Image")
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
     *                  ref="#/definitions/Image"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateImageAPIRequest $request)
    {
        $input = $request->all();

        /** @var Image $image */
        $image = $this->imageRepository->findWithoutFail($id);

        if (empty($image)) {
            return $this->sendError('Image not found');
        }

        $image = $this->imageRepository->update($input, $id);

        return $this->sendResponse($image->toArray(), 'Image updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/images/{id}",
     *      summary="Remove the specified Image from storage",
     *      tags={"Image"},
     *      description="Delete Image",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Image",
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
        /** @var Image $image */
        $image = $this->imageRepository->findWithoutFail($id);

        if (empty($image)) {
            return $this->sendError('Image not found');
        }
        $url =  $image->findImage();
        if (Storage::disk('public')->exists($url)) {
                Storage::delete($url);
            }
        // return $url;
        $image->delete();

        return $this->sendResponse($id, 'Image deleted successfully');
    }
    public function checkImageExits($user_id, $pet_id, $input){
        if(!empty($pet_id)){
            $url = 'avatars/pets/'.$pet_id.'/';
            $oldImage = $this->imageRepository->findWhere(['pet_id'=>$pet_id])->first();
        }else{
            $url = 'avatars/users/'.$user_id.'/';
            $oldImage = $this->imageRepository->findWhere(['user_id'=>$user_id,'pet_id'=>null])->first();
        }
        if (!empty($oldImage)) {
            $storagepath = $url.$oldImage->url;
            if (Storage::disk('public')->exists($storagepath)) {
                Storage::delete($storagepath);
            }
            $oldImage->delete();
        }

        $image = $this->imageRepository->upload($input);

        return $image;
    }

    // public function uploadAvatar(CreateImageAPIRequest $request){

    //     $input = $request->all();
    //     $user_id = $input['user_id'];

    //     $pet_id = $request->input('pet_id');
    //     if($pet_id){
    //         $input['pet_id']=$pet_id;
    //     }else{
    //         $input['pet_id']=null;
    //     }

    //     if(!empty($pet_id)){
    //         $url = 'avatars/pets/'.$pet_id.'/';
    //         $oldImage = $this->imageRepository->findWhere(['pet_id'=>$pet_id])->first();
    //     }else{
    //         $url = 'avatars/users/'.$user_id.'/';
    //         $oldImage = $this->imageRepository->findWhere(['user_id'=>$user_id,'pet_id'=>null])->first();
    //     }




    //     $image = $this->checkImageExits($user_id, $pet_id, $input);
    //     return $this->sendResponse($image->toArray(), 'Image saved successfully');
    // }

    // public function getAvatar(Request $request){
    //     $input = $request->all();
    //     if($request->input('user_id') && !$request->input('pet_id')){
    //         $user_id = $input['user_id'];
    //         $image = $this->imageRepository->findWhere(['user_id'=>$user_id,'pet_id'=>null])->first();
    //         $name = $image['url'];
    //         if (empty($image)) {
    //             // $storagepath = 'avatars/defoult/no-avatar.jpg';
    //             return $this->sendError('Image not found');
    //         }
    //         else{
    //             $storagepath = 'avatars/users/'.$user_id.'/'.$name;
    //             if (!Storage::disk('public')->exists($storagepath)) {
    //             return $this->sendError('Image not found');
    //             }
    //         }
    //     }elseif ($request->input('pet_id')) {
    //         $pet_id = $input['pet_id'];
    //         $image = $this->imageRepository->findWhere(['pet_id'=>$pet_id])->first();
    //         $name = $image['url'];
    //         if (empty($image)) {
    //             return $this->sendError('Image not found');
                
    //         }
    //         else{
    //             $storagepath = 'avatars/pets/'.$pet_id.'/'.$name;
    //             if (!Storage::disk('public')->exists($storagepath)) {
    //             return $this->sendError('Image not found');
                
    //             }
    //         }
    //     }else{
    //         return $this->sendError('Avatar not found');
    //     }
        
    //     $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($storagepath);
    //     $headers = ['Content-Type' => 'image/jpeg'];
    //     return response()->file($path, $headers);
    // }

    public function uploadFiles(Request $request){

        $input = $request->all();

        if(!array_has($input,  'pet_id')){
            $input = array_add($input, 'pet_id', null);
        }

        $user_id = $input['user_id'];

        $pet_id = $input['pet_id'];

        if(!empty($pet_id)){
            $url = 'pets/'.$pet_id.'/';
            $oldImage = $this->imageRepository->findWhere(['pet_id'=>$pet_id])->first();
        }else{
            $url = 'users/'.$user_id.'/';
            $oldImage = $this->imageRepository->findWhere(['user_id'=>$user_id,'pet_id'=>null,'is_avatar'=>1])->first();
        }

        if (!empty($oldImage)) {
            $storagepath = $url.$oldImage->url;
            if (Storage::disk('public')->exists($storagepath)) {
                Storage::delete($storagepath);
            }
            $oldImage->delete();
        }

        $image = $this->imageRepository->uploadFiles($input);

        return $this->sendResponse($image, 'Image saved successfully');
    }


    public function getAvatarPublic(Request $request) {
        // edit getAvatar puclic
        $input = $request->all();
        if($request->input('user_id') && !$request->input('pet_id')){
            $user_id = $input['user_id'];
            $image = $this->imageRepository->findWhere(['user_id'=>$user_id,'pet_id'=>null,'is_avatar'=>true])->first();
            if (empty($image)) {
                return $url = url('/storage/default/default-avatar.jpg');
            }
            else{
                $name = $image['url'];
                if(Storage::disk('public')->exists('users/'.$user_id.'/'.$name)){
                    $url = url('/storage/users/'.$user_id .'/'.$name);
                }else{
                    $url = url('/storage/default/default-avatar.jpg');
                }
                return $url;
            }
        }elseif ($request->input('pet_id')) {
            $pet_id = $input['pet_id'];
            $image = $this->imageRepository->findWhere(['pet_id'=>$pet_id,'is_avatar'=>true])->first();
            if (empty($image)) {
                return $url = url('/storage/default/no-avatar-pet.jpg');
            }
            else{
                $name = $image['url'];
                if(Storage::disk('public')->exists('pets/'.$pet_id.'/'.$name)){
                    $url = url('/storage/pets/'.$pet_id .'/'.$name);
                }else{
                    $url = url('/storage/default/no-avatar-pet.jpg');
                }
                return $url;
            }
        }else{
            return $url = url('/storage/default/no-image.png');
        }

        // $input = $request->all();
        // if($request->input('user_id') && !$request->input('pet_id')){
        //     $user_id = $input['user_id'];
        //     $image = $this->imageRepository->findWhere(['user_id'=>$user_id,'pet_id'=>null])->first();
        //     $name = $image['url'];
        //     if (empty($image)) {
        //         $storagepath = 'avatars/defoult/no-avatar.jpg';
        //         // return $this->sendError('Image not found');
        //     }
        //     else{
        //         $storagepath = 'users/'.$user_id.'/'.$name;
        //         if (!Storage::disk('public')->exists($storagepath)) {
        //             $storagepath = 'avatars/defoult/no-avatar.jpg';
        //         }
        //     }
        // }elseif ($request->input('pet_id')) {
        //     $pet_id = $input['pet_id'];
        //     $image = $this->imageRepository->findWhere(['pet_id'=>$pet_id])->first();
        //     $name = $image['url'];
        //     if (empty($image)) {
        //         // return $this->sendError('Image not found');
        //         $storagepath = 'avatars/defoult/no-avatar.jpg';
        //     }
        //     else{
        //         $storagepath = 'avatars/pets/'.$pet_id.'/'.$name;
        //         if (!Storage::disk('public')->exists($storagepath)) {
        //         // return $this->sendError('Image not found');
        //             $storagepath = 'avatars/defoult/no-avatar.jpg';
                
        //         }
        //     }
        // }else{
        //     // return $this->sendError('Avatar not found');
        //     $storagepath = 'avatars/defoult/no-avatar.jpg';
        // }
        
        // $path = Storage::disk('public')->getDriver()->getAdapter()->applyPathPrefix($storagepath);
        // $headers = ['Content-Type' => 'image/jpeg'];
        // return response()->file($path, $headers);
    }
    public function getUrlImage($id){
        $image = $this->imageRepository->getUrlImage($id);

        if (empty($image)) {
            return $this->sendError('Image not found');
        }

        return $this->sendResponse($image->toArray(), 'Image retrieved successfully');
    }
    public function delImages(Request $request){
        $input = $request->all();
        $images = Image::whereIn('id',$input['ids'])->get();
        foreach ($images as $key => $image) {
            $url =  $image->findImage();
            if (Storage::disk('public')->exists($url)) {
                    Storage::delete($url);
                }
            // return $url;
            $image->delete();
        }
        return $this->sendResponse([], 'Images delete successfully'); 
    }
}
