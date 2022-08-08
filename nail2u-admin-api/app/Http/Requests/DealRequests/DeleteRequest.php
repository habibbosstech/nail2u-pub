<?php

namespace App\Http\Requests\DealRequests;

use App\Http\Requests\BaseRequest;
use App\Rules\CheckDeletedDeal;

class DeleteRequest extends BaseRequest
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
            'id' => ['bail','integer', new CheckDeletedDeal()]
        ];
    }
}
