<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
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
            'code' => 'nullable|unique:products,code',
            'title' => 'required|min:4',
            'vendor_id' => 'required|exists:vendors,id'
        ];
        if($id = $this->segment(2)) {
            $rules['code'] = 'nullable|unique:products,code,' . $id;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'code.unique' => 'Product already exists with the given Code'
        ];
    }
}
