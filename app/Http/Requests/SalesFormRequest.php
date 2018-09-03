<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesFormRequest extends FormRequest
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
            'outlet_id' => 'required|exists:outlets,id',
            'memo' => 'required|unique:sales,memo'
        ];
        if($id = $this->segment(2)) {
            $rules['memo'] = 'nullable|unique:sales,memo,' . $id;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'memo.unique' => 'Invoice/Sales order no. already exists'
        ];
    }
}
