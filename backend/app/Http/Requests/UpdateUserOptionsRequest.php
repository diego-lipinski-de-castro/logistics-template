<?php

namespace App\Http\Requests;

use App\Helper;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserOptionsRequest extends FormRequest
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
        $chargeStyleOptions = implode(',', array_keys(User::CHARGE_STYLES));
        $receiptConfirmationOptions = implode(',', array_keys(User::RECEIPT_CONFIRMATION));

        return [
            'charge_style' => "required|in:$chargeStyleOptions",
            'receipt_confirmation' => "required|in:$receiptConfirmationOptions",
            'delivery_constraint' => 'required|in:OPEN,PARTIAL,BLOCK',
            'return_fee_paid' => 'nullable|integer',
            'return_fee_charged' => 'nullable|integer',
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
            'return_fee_paid' => Helper::extractNumbersFromString($this->return_fee_paid),
            'return_fee_charged' => Helper::extractNumbersFromString($this->return_fee_charged),
        ]);
    }
}
