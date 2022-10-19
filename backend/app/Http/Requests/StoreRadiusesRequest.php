<?php

namespace App\Http\Requests;

use App\Helper;
use Illuminate\Foundation\Http\FormRequest;

class StoreRadiusesRequest extends FormRequest
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
            'radiuses' => 'required|array',
            'radiuses.*.rad' => 'nullable|integer',
            'radiuses.*.time' => 'required|integer',
            'radiuses.*.paid' => 'required|integer',
            'radiuses.*.charged' => 'required|integer',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->replace([
            'radiuses' => collect($this->radiuses)->map(function ($item) {
                return [
                    'rad' => $item['rad'],
                    'time' => $item['time'],
                    'paid' => Helper::extractNumbersFromString($item['formatted_paid']),
                    'charged' => Helper::extractNumbersFromString($item['formatted_charged']),
                ];
            })->toArray(),
        ]);
    }
}
