<?php
namespace App\Helpers;

/*
 * Author: Raksa Eng
 */

class LoanCalculator
{
    /**
     * Calculate monthly for monthly replayment
     * @param &$bag
     * @param $typeId
     * @param float $loanAmount
     * @param float $monthlyInterestRate
     * @param float $repaymentMonthNumber
     */
    public static function calculateMonthlyRepayment(
        &$bag, $typeId,
        float $loanAmount,
        float $monthlyInterestRate,
        float $repaymentMonthNumber
    ) {
        $bag = [];
        if (LoanType::isStandard($typeId)) {

            // Base simple explain
            // with the most typical formula https://en.wikipedia.org/wiki/Loan
            $realMonthlyInterestRate = $monthlyInterestRate / 100;
            $result = $loanAmount * $realMonthlyInterestRate * \pow(1 + $realMonthlyInterestRate, $repaymentMonthNumber);
            $result = $result / (\pow(1 + $realMonthlyInterestRate, $repaymentMonthNumber) - 1);

            $bag['message'] = LoanType::STANDARD['description'];
            return $result;
        }
        $bag['message'] = 'Unknown loan type';
        return null;
    }
}
