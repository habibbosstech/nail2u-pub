<?php

namespace App\Http\Requests\Carousel;

use App\Http\Requests\BaseRequest;

class UploadRequest extends BaseRequest
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
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ];
    }
}
