<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\BaseRequest;

class AddTaskRequest extends BaseRequest
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
            'name' => "required",
            'due_date' => ['required', 'date_format:d-m-Y', 'after_or_equal:' . now()->format('d-m-Y')]
        ];
    }
}
