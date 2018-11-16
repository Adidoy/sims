<?php

namespace App\Http\Requests\UnitRequest;

use App\Models\Supply\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UnitUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::check()) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $unit = Unit::findOrFail($this->unit);
        return [
			'name' => 'required|string|unique:units,name,' . $unit->name . ',name',
			'description' => 'required|string|max:256',
			'abbreviation' => 'required|string'
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
