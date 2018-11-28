<?php

namespace App\Transformers\Api\V1\Site;

use League\Fractal\TransformerAbstract;
use App\Models\Fence;
use App\Models\Pet;

/**
 * Class FenceTransformer
 * @package namespace App\Transformers\Api\V1\Site;
 */
class FenceTransformer extends TransformerAbstract
{

    /**
     * Transform the \Fence entity
     * @param \Fence $model
     *
     * @return array
     */
    public function transform(Fence $fence)
    {
        foreach ($fence->pets as $key => $value) {
            $fence->pets[$key]['avatar'] = $value->getAvatar();
        }

        return [
            'id'         => (int) $fence->id,
            'name'       => $fence->name,
            'company_id' => $fence->company_id,
            'user_id' => $fence->user_id,
            'fence_data' => $fence->fence_data,
            'pets' => $fence->pets
        ];
    }
}
