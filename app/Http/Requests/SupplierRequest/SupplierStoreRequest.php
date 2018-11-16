<?php

namespace App\Http\Requests\SupplierRequest;

use Illuminate\Foundation\Http\FormRequest;

class SupplierStoreRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'email',
            'address' => 'nullable|max:256',
            'contact' => 'nullable|max:256',
            'website' => 'nullable|max:256',
        ];
    }
}
