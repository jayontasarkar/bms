<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileFormRequest extends FormRequest
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
        $id = auth()->id();

        return [
            'name' => 'required',
            'email' => 'nullable|unique:users,email,' . $id,
            'phone' => 'nullable|unique:users,phone,' . $id,
            'username' => 'required|unique:users,username,' . $id,
        ];
    }
}
