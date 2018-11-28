<?php

namespace App\Transformers\Api\V1\Site;

use League\Fractal\TransformerAbstract;
use App\Models\Pet;

/**
 * Class FenceTransformer
 * @package namespace App\Transformers\Api\V1\Site;
 */
class PetTransformer extends TransformerAbstract
{

    /**
     * Transform the \Pet entity
     * @param \Pet $model
     *
     * @return array
     */
    public function transform(Pet $pet)
    {
        if($pet->device){
            $location = $pet->device->locations->last();
        }else{
            $location = null;
        }

        if($pet->device){
            $device = [
                'id' => $pet->device->id,
                'UUID' => $pet->device->UUID,
                'imei' => $pet->device->imei,
                'name' => $pet->device->name,
                'battery' => $pet->device->battery,
                'phone' => $pet->device->phone,
                'mode' => $pet->device->mode,
                'company_id' => $pet->device->company_id,
            ];
        }else{
            $device = null;
        }

        return [
            'id'         => (int) $pet->id,
            'name'       => $pet->name,
            'description' => $pet->description,
            'avatar' => $pet->getAvatar(),
            'location' => $location,
            'device' => $device,
            'fence_id' => $pet->fence_id,
        ];
    }
}
