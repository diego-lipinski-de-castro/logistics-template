<?php

namespace App\Http\Requests;

use Ajthinking\LaravelPostgis\Geometries\Point;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
            'position' => 'required|array|size:2',
            'street_number' => 'required',
            'street_name' => 'required',
            'district' => 'required',
            'city' => 'required|exists:cities,name',
            'state' => 'required',
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @param  string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null)
    {
        // return data_get($this->validator->validated(), $key, $default);

        return array_merge(parent::validated(), [
            'position' => new Point($this->position[0], $this->position[1]),
        ]);
    }
}
