<?php

namespace App\Http\Requests\SupplyRequest;

use App\Supply;
use Illuminate\Foundation\Http\FormRequest;

class SupplyUpdateRequest extends FormRequest
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
        $supply = Supply::findOrFail($this->supply);
        return [
                'stocknumber' => 'required|unique:supplies,stocknumber,' . $supply->stocknumber . ',stocknumber',
                'details' => 'required',
                'unit' => 'required',
                'reorderpoint' => 'integer'
        ];
    }
    
    public function validationData()
    {
        if (method_exists($this->route(), 'parameters')) {
            $this->request->add($this->route()->parameters('id'));
            $this->query->add($this->route()->parameters('id'));

            return array_merge($this->route()->parameters(), $this->all());
        }

        return $this->all();
    }
}
