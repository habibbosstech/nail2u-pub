<?php

namespace App\Http\Requests\ArtistRequests;

use App\Http\Requests\BaseRequest;

class ListAllRequest extends BaseRequest
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
            'items_per_page'=>'integer|between:1,100',
            'search'=>'string|max:255'
        ];
    }
}
