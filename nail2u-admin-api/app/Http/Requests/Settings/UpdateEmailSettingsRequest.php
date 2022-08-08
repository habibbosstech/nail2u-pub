<?php

namespace App\Http\Requests\Settings;

use App\Http\Requests\BaseRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class UpdateEmailSettingsRequest extends BaseRequest
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
    public function rules(User $user)
    {
        return [
            'email' => 'bail|max:255|min:4,required|email|unique:users,email, ' . auth()->user()->id . ',id',
            'password' => 'bail|required|max:12|min:8',
            'conform_password' => 'required|same:password',
        ];
    }
}
