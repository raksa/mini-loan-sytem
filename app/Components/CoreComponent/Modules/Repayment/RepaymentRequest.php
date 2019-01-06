<?php

namespace App\Components\CoreComponent\Modules\Repayment;

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
        return [
            'loan_id' => 'required|exists:loans,id',
            'amount' => 'required|numeric',
            'payment_status' => 'required|numeric',
            'payment_status' => 'required',
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
            'loan_id' . '.required' => trans('default.repayment_loan_id_required'),
            'loan_id' . '.exists' => trans('default.repayment_loan_not_found'),
            'amount' . '.required' => trans('default.repayment_amount_required'),
            'amount' . '.numeric' => trans('default.repayment_amount_must_numeric'),
            'payment_status' . '.required' => trans('default.repayment_status_id_required'),
            'payment_status' . '.numeric' => trans('default.repayment_status_id_must_numeric'),
            'payment_status' . '.required' => trans('default.repayment_due_date_required'),
        ];
    }
}
