<?php

namespace App\Http\Requests;

use App\Helper;
use Illuminate\Foundation\Http\FormRequest;

class StoreChargeOptionsRequest extends FormRequest
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
        if ($this->charge_style == 'DAILY') {
            return [
                'charge_options' => 'required|array',
                'charge_options.period' => 'required|integer|min:120|max:720',
                'charge_options.price' => 'required|integer',
                'charge_options.markup' => 'required|integer',
            ];
        }

        if ($this->charge_style == 'PACKAGE') {
            return [
                'charge_options' => 'required|array',
                'charge_options.price' => 'required|integer',
                'charge_options.markup' => 'required|integer',
            ];
        }

        if ($this->charge_style == 'OPEN') {
            return [
                'charge_options' => 'required|array',
                'charge_options.markup' => 'required|integer',
            ];
        }

        return [];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->charge_style == 'DAILY') {
            $this->merge([
                'charge_options' => [
                    'period' => $this->charge_options['period'],
                    'price' => Helper::extractNumbersFromString($this->charge_options['price']),
                    'markup' => Helper::extractNumbersFromString($this->charge_options['markup']),
                ],
            ]);
        }

        if ($this->charge_style == 'PACKAGE') {
            $this->merge([
                'charge_options' => [
                    'price' => Helper::extractNumbersFromString($this->charge_options['price']),
                    'markup' => Helper::extractNumbersFromString($this->charge_options['markup']),
                ],
            ]);
        }

        if ($this->charge_style == 'OPEN') {
            $this->merge([
                'charge_options' => [
                    'markup' => Helper::extractNumbersFromString($this->charge_options['markup']),
                ],
            ]);
        }
    }
}
