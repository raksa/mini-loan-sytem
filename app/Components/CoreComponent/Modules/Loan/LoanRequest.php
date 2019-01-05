<?php

namespace App\Components\CoreComponent\Modules\Loan;

use App\Rules\RepaymentFrequencyTypeRule;
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
        return [
            "amount" => "required|numeric|min:1",
            "duration" => "required|numeric|min:1",
            "repayment_frequency" => ["required", "numeric", new RepaymentFrequencyTypeRule()],
            "interest_rate" => "required|numeric",
            "arrangement_fee" => "required|numeric",
            "date_contract_start" => "required",
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
            "amount.required" => trans("default.loan_amount_required"),
            "amount.numeric" => trans("default.loan_amount_must_numeric"),
            "amount.minx" => trans("default.loan_amount_must_greater_1"),
            "duration.required" => trans("default.loan_duration_required"),
            "duration.numeric" => trans("default.loan_duration_must_numeric"),
            "duration.min" => trans("default.loan_duration_must_greater_1"),
            "repayment_frequency.required" => trans("default.loan_repay_freq_required"),
            "repayment_frequency.numeric" => trans("default.loan_repay_freq_must_numeric"),
            "interest_rate.required" => trans("default.loan_int_rate_required"),
            "interest_rate.numeric" => trans("default.loan_int_rate_must_numeric"),
            "arrangement_fee.required" => trans("default.loan_arr_fee_required"),
            "arrangement_fee.numeric" => trans("default.loan_arr_fee_must_numeric"),
            "date_contract_start.required" => trans("default.loan_cont_start_required"),
        ];
    }
}
