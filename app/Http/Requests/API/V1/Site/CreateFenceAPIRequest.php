<?php

namespace App\Http\Requests\Api\V1\Site;

use App\Models\Fence;
use InfyOm\Generator\Request\APIRequest;

class CreateFenceAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required',
            'name' => 'required',
            'fence_data' => 'required',
            // 'pet_ids' => 'required'
        ];
    }
}
