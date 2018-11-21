<?php

namespace App\Components\MiniAspire\Modules\Repayment;

use Illuminate\Foundation\Http\FormRequest;

/*
 * Author: Raksa Eng
 */

class RepaymentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return static::staticRules();
    }

    public static function staticRules()
    {
        // TODO: update validation rules
        return [
            Repayment::AMOUNT => 'required|numeric',
        ];
    }

    /**
     * Get the validation custom error message.
     *
     * @return array
     */
    public function messages()
    {
        return static::staticMessages();
    }
    public static function staticMessages()
    {
        return [
            Repayment::AMOUNT . 'required' => trans('default.repayment_amount_required'),
            Repayment::AMOUNT . 'numeric' => trans('default.repayment_amount_numeric'),
        ];
    }
}
