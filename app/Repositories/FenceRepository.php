<?php

namespace App\Repositories;

use App\Models\Fence;
use App\Models\Pet_fence;
use App\Models\FenceData;
use App\Models\Pet;
use InfyOm\Generator\Common\BaseRepository;
use Illuminate\Support\Facades\DB;

class FenceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'company_id',
        'geo_data'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Fence::class;
    }

    public function getFence($id){
        $fence = Fence::where('id', $id)->with('fence_data', 'pets')->first();

        return $fence;
    }

    public function createFence($attributes){

        $fence = Fence::create([
            'user_id' => $attributes['user_id'],
            'name' => $attributes['name'],
            'company_id' => $attributes['company_id'],
        ]);

        foreach ($attributes['fence_data'] as $data) {
            $fence->fence_data()->create([
                'lat' => $data['lat'],
                'long' => $data['lng']
            ]);
        }
        if(array_has($attributes, 'pet_ids')){
            foreach ($attributes['pet_ids'] as $value) {
                $pet = Pet::find($value);

                $pet->fence_id = $fence->id;

                $pet->save();
            }
        }

        return $fence;

    }

    public function getFenceOfPet($id){
        $pet_fence = Pet_fence::where('pet_id',$id)->latest()->first();
        if($pet_fence){
            $fence = $pet_fence->fence;
            if($fence){
                $fence_data = $fence->fence_data;
                return $fence_data;
            }else{
                return null;
            }
        }else{
            return null;
        }
    }

    public function getAllFence(){
        $fences = Fence::all();
        foreach ($fences as $key => $fence) {
            $fence->fence_data;
            $fence->pet_fence;
            if($fence->pet_fence){
                $fence->pet_fence->pet;
            }
            $fence->user;
        }
        return $fences;
    }

    public function deleteFence($id){

        $fence = Fence::find($id);

        FenceData::where('fence_id',$id)->delete();
        Pet::where('fence_id', $id)->update([
            'fence_id' => null
        ]);

        $fence->delete();

        return $fence;

    }

    public function getFenceOfUser($id){
        $fences = \App\Models\Fence::where('user_id', $id)
            ->orderBy('id', 'DESC')
            ->with('fence_data', 'pet_fence.pet.image')
            ->select('id', 'name')
            ->get();
        return $fences;
    }

    public function addFenceForPet(array $attributes){
        $del = Pet_fence::where('pet_id',$attributes['pet_id'])->delete();
        $addFence = Pet_fence::create($attributes);
        return $addFence;
    }

    public function updateFence($attributes, $id){
        // delete old fence data
        FenceData::where('fence_id',$id)->delete();

        Pet::where('fence_id', $id)->update([
            'fence_id' => null
        ]);

        $fence = Fence::find($id);
        $fence->name = $attributes['name'];
        $fence->save();

        foreach ($attributes['fence_data'] as $data) {
            $fence->fence_data()->create([
                'lat' => $data['lat'],
                'long' => $data['lng']
                ]);
        }

        if(array_has($attributes, 'pet_ids')){
            foreach ($attributes['pet_ids'] as $value) {
                $pet = Pet::find($value);

                $pet->fence_id = $fence->id;

                $pet->save();
            }
        }

        return $fence;
    }

    public function getPetFence($id){
        $petFence = Pet_fence::where('pet_id',$id)->latest()->first();
        if($petFence){
            $petFence->fence;
            return $petFence;
        }else{
            return null;
        }
    }
}
