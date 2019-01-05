<?php

namespace App\Components\CoreComponent\Modules\Repayment;

use App\Components\CoreComponent\Modules\Loan\Loan;
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
            Repayment::LOAN_ID => 'required|exists:' . Loan::TABLE_NAME . ',' . Loan::ID,
            Repayment::AMOUNT => 'required|numeric',
            Repayment::PAYMENT_STATUS => 'required|numeric',
            Repayment::DUE_DATE => 'required',
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
            Repayment::LOAN_ID . '.required' => trans('default.repayment_loan_id_required'),
            Repayment::LOAN_ID . '.exists' => trans('default.repayment_loan_not_found'),
            Repayment::AMOUNT . '.required' => trans('default.repayment_amount_required'),
            Repayment::AMOUNT . '.numeric' => trans('default.repayment_amount_must_numeric'),
            Repayment::PAYMENT_STATUS . '.required' => trans('default.repayment_status_id_required'),
            Repayment::PAYMENT_STATUS . '.numeric' => trans('default.repayment_status_id_must_numeric'),
            Repayment::DUE_DATE . '.required' => trans('default.repayment_due_date_required'),
        ];
    }
}
