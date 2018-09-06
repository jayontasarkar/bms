<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutletFormRequest extends FormRequest
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
            'phone' => 'nullable|unique:outlets,phone',
            'thana_id' => 'required|exists:thanas,id'
        ];
        if($id = $this->segment(2)) {
            $rules['phone'] = 'nullable|unique:outlets,phone,' . $id;
        }else{
            $rules['memo'] = 'nullable|unique:sales,memo';
        }
        return $rules;
    }
}
