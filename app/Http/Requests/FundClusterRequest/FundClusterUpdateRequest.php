<?php

namespace App\Http\Requests\FundClusterRequest;

use Illuminate\Foundation\Http\FormRequest;

class FundClusterUpdateRequest extends FormRequest
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
            'code' => 'required|unique:fundclusters,code,' . $fundcluster->code . ',code',
            'description' => 'required'
        ];
    }
}
