<?php

namespace App\Components\CoreComponent\Modules\Loan;

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
            Loan::AMOUNT => 'required|numeric',
            Loan::DURATION => 'required|numeric',
            Loan::REPAYMENT_FREQUENCY => 'required|numeric',
            Loan::INTEREST_RATE => 'required|numeric',
            Loan::ARRANGEMENT_FEE => 'required|numeric',
            Loan::DATE_CONTRACT_START => 'required',
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
            Loan::AMOUNT . '.required' => trans('default.loan_amount_required'),
            Loan::AMOUNT . '.numeric' => trans('default.loan_amount_must_numeric'),
            Loan::DURATION . '.required' => trans('default.loan_duration_required'),
            Loan::DURATION . '.numeric' => trans('default.loan_duration_must_numeric'),
            Loan::REPAYMENT_FREQUENCY . '.required' => trans('default.loan_repay_freq_required'),
            Loan::REPAYMENT_FREQUENCY . '.numeric' => trans('default.loan_repay_freq_must_numeric'),
            Loan::INTEREST_RATE . '.required' => trans('default.loan_int_rate_required'),
            Loan::INTEREST_RATE . '.numeric' => trans('default.loan_int_rate_must_numeric'),
            Loan::ARRANGEMENT_FEE . '.required' => trans('default.loan_arr_fee_required'),
            Loan::ARRANGEMENT_FEE . '.numeric' => trans('default.loan_arr_fee_must_numeric'),
            Loan::DATE_CONTRACT_START . '.required' => trans('default.loan_cont_start_required'),
        ];
    }
}
