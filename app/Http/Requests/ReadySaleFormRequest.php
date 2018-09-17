<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReadySaleFormRequest extends FormRequest
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
            'memo' => 'required|unique:ready_sales,memo,' . $this->segment(2),
            'vendor_id' => 'required|exists:vendors,id',
            'ready_sale_details' => 'required'
        ];
    }
}
