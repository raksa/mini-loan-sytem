<?php
namespace App\Components\CoreComponent\Modules\Repayment;

use App\Components\CoreComponent\Modules\Loan\Loan;
use App\Helpers\LoanCalculator;
use Validator;

/*
 * Author: Raksa Eng
 */
class RepaymentRepository
{
    /**
     * Create Repayment
     *
     * @param array $data
     * @return \App\Components\CoreComponent\Modules\Repayment\Repayment|null
     */
    public function createRepayment(&$bag, $data = [])
    {
        $validator = Validator::make($data, RepaymentRequest::staticRules(),
            RepaymentRequest::staticMessages());
        if ($validator->fails()) {
            $bag = [
                "message" => trans("default.validation_error"),
                "errors" => $validator->errors(),
            ];
            return null;
        }
        $repayment = new Repayment();
        $repayment->setProps($data);
        if ($repayment->save()) {
            return $repayment;
        }
        $bag = [
            "message" => trans("default.saving_fail"),
        ];
        return null;
    }

    /**
     * Generate Repayments
     *
     * @param array $bag
     * @param \App\Components\CoreComponent\Modules\Loan\Loan $loan
     * @return boolean
     */
    public function generateRepayments(&$bag, Loan $loan)
    {
        $frequencyType = $loan->getRepaymentFrequencyTypeId();
        $monthDuration = $loan->getMonthsDuration();
        $calData = [
            $loan->getAmount(),
            $loan->getMonthlyInterestRate(),
            $loan->getMonthsDuration(),
        ];
        if (RepaymentFrequency::isMonthly($frequencyType)) {
            $amount = LoanCalculator::calculateMonthlyRepayment(...$calData);
        } else if (RepaymentFrequency::isFortnightly($frequencyType)) {
            $amount = LoanCalculator::calculateFortnightlyRepayment(...$calData);
        } else {
            $amount = LoanCalculator::calculateWeeklyRepayment(...$calData);
        }
        $startDate = $loan->getDateContractStart();
        $endDate = $loan->getDateContractEnd();
        $dueDate = $startDate->copy();
        while (true) {
            if (RepaymentFrequency::isMonthly($frequencyType)) {
                $dueDate = $dueDate->copy()->addMonth(1);
                $dueDate->day = $endDate->day;
            } else if (RepaymentFrequency::isFortnightly($frequencyType)) {
                $dueDate = $dueDate->copy()->addDay(14);
            } else {
                $dueDate = $dueDate->copy()->addDay(7);
            }
            if ($dueDate->greaterThan($endDate)) {
                break;
            }
            if (!$this->createRepayment($bag, [
                Repayment::LOAN_ID => $loan->getId(),
                Repayment::AMOUNT => $amount,
                Repayment::PAYMENT_STATUS => RepaymentStatus::UNPAID["id"],
                Repayment::DUE_DATE => $dueDate . '',
                Repayment::DATE_OF_PAYMENT => null,
                Repayment::REMARKS => null,
            ])) {
                return false;
            }
        }
        return true;
    }
}
