<?php

namespace App\Http\Requests\OfficeRequest;

use App\Models\Office\Office;
use Illuminate\Foundation\Http\FormRequest;

class OfficeUpdateRequest extends FormRequest
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
        $office = Office::findOrFail($this->office);
        return [
            'code' => 'required|max:20|unique:offices,code,'.$office->code.',code',
            'name' => 'required|max:200|unique:offices,name,'.$office->name.',name',
            'description' => 'max:200',
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
