<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'username' => [
                'required',
                Rule::unique('users')->ignore(request('id'))
            ],
            'password' => [
                request()->has('id') ? '' : 'required',
                'min:6'
            ],
            'first_name' => ['required', 'max:255'],
            'middle_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            // 'ext_name' => ['required', 'max:255'],
            'user_role' => ['required', 'max:255'],
            'swad_office_id' => ['required', 'max:255'],
        ];
    }
}
