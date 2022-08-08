<?php

namespace App\Http\Requests\DealRequests;

use App\Http\Requests\BaseRequest;

class AddNewRequest extends BaseRequest
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
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_date' => 'required|date|date_format:Y-m-d|after:start_date',
            'image_url' => 'image|mimes:jpeg,png,jpg|max:2048',
            'discount' => 'required|integer|255'
        ];
    }
}
