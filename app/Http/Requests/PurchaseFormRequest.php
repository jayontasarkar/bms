<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseFormRequest extends FormRequest
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
            'vendor_id' => 'required|exists:vendors,id',
            'memo' => 'required|unique:purchases,memo'
        ];
        if($id = $this->segment(2)) {
            $rules['memo'] = 'nullable|unique:purchases,memo,' . $id;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'memo.unique' => 'Invoice/Purchase order no. already exists'
        ];
    }
}
