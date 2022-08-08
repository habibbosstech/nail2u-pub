<?php

namespace App\Http\Requests\Carousel;

use App\Http\Requests\BaseRequest;

class UpdateRequest extends BaseRequest
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
            'id' => 'required|numeric|exists:app_images,id',
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ];
    }
}
