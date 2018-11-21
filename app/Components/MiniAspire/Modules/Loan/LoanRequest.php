<?php

namespace App\Components\MiniAspire\Modules\Loan;

use Illuminate\Foundation\Http\FormRequest;

/*
 * Author: Raksa Eng
 */

class LoanRequest extends FormRequest
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
            Loan::AMOUNT => 'required|numeric',
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
            Loan::AMOUNT . 'required' => trans('default.loan_amount_required'),
            Loan::AMOUNT . 'numeric' => trans('default.loan_amount_numeric'),
        ];
    }
}
