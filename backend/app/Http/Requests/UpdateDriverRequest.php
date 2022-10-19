<?php

namespace App\Http\Requests;

use App\Helper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverRequest extends FormRequest
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
            'city_id' => 'required|exists:cities,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            
            'email' => 'nullable|string|max:255',
            'phone' => 'required|phone:BR|unique:drivers,phone,' . $this->driver->id,
            
            'birthday' => 'nullable',
            'cpf' => 'nullable|unique:drivers,cpf,' . $this->driver->id,
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'phone' => Helper::toPhone($this->phone, true),
        ]);
    }
}
