<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersFormRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|unique:users,phone',
            'username' => 'nullable|unique:users,username',
            'password' => 'required|min:4|confirmed'
        ];
        if($id = $this->segment(2)) {
            $rules['email'] = 'nullable|email|unique:users,email,' . $id;
            $rules['phone'] = 'nullable|unique:users,phone,' . $id;
            $rules['username'] = 'nullable|email|unique:users,username,' . $id;
        }
        return $rules;
    }
}
