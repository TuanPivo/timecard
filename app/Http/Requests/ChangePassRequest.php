<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangePassRequest extends FormRequest
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
            'passwordNew' => [
                'required',
                'min:8',
                'regex:/^(?=.*[!@#$%^&*()\-_=+{};:,<.>ยง~`|[\]\\/"\'])/'
            ],
            'confirm_password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[!@#$%^&*()\-_=+{};:,<.>ยง~`|[\]\\/"\'])/',
                'same:passwordNew'
            ],
        ];

    }
    public function messages()
    {
        return [
            'passwordNew.required' => 'New password is required',
            'passwordNew.min' => 'Password must have a minimum length of 8 characters',
            'passwordNew.regex' => 'Password must contain at least one special character',
            'confirm_password.required' => 'Confirem password is required',
            'confirm_password.min' => 'Password must have a minimum length of 8 characters',
            'confirm_password.regex' => 'Password must contain at least one special character',
            'confirm_password.same' => 'The confirmation password must be the same as the new password',
        ];
    }
}
