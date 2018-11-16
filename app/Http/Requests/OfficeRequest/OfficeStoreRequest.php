<?php

namespace App\Http\Requests\OfficeRequest;

use App\Models\Office\Office;
use Illuminate\Foundation\Http\FormRequest;

class OfficeStoreRequest extends FormRequest
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
			'code' => 'required|max:20|unique:offices,code',
			'name' => 'required|max:200',
			'description' => 'max:200'
        ];
    }
}
