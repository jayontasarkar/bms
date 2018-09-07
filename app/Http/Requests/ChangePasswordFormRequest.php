<?php

namespace App\Http\Requests;

use App\Rules\PasswordHashCheck;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordFormRequest extends FormRequest
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
            'current_password'      => ['required', new PasswordHashCheck()],
            'password'              => 'required|min:4|confirmed',
            'password_confirmation' => 'required'
        ];
    }
}
