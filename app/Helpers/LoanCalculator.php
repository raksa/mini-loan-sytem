<?php
namespace App\Helpers;

/*
 * Author: Raksa Eng
 */

class LoanCalculator
{
    /**
     * Calculate amount for monthly repayment
     * @param $typeId
     * @param float $loanAmount
     * @param float $monthlyInterestRate
     * @param float $repaymentMonthNumber
     */
    public static function calculateMonthlyRepayment(
        float $loanAmount,
        float $monthlyInterestRate,
        float $repaymentMonthNumber
    ) {
        // Base simple explain
        // with the most typical formula https://en.wikipedia.org/wiki/Loan
        $realMonthlyInterestRate = $monthlyInterestRate / 100;
        $result = $loanAmount * $realMonthlyInterestRate * \pow(1 + $realMonthlyInterestRate, $repaymentMonthNumber);
        $result = $result / (\pow(1 + $realMonthlyInterestRate, $repaymentMonthNumber) - 1);

        return $result;
    }
    /**
     * Calculate amount for fortnightly repayment
     * @param $typeId
     * @param float $loanAmount
     * @param float $monthlyInterestRate
     * @param float $repaymentMonthNumber
     */
    public static function calculateFortnightlyRepayment(
        float $loanAmount,
        float $monthlyInterestRate,
        float $repaymentMonthNumber
    ) {
        // FIXME: make correct formula
        $realMonthlyInterestRate = $monthlyInterestRate / 100;
        $repaymentFortnightNumber = $repaymentMonthNumber * 2;
        $result = $loanAmount * $realMonthlyInterestRate * \pow(1 + $realMonthlyInterestRate, $repaymentFortnightNumber);
        $result = $result / (\pow(1 + $realMonthlyInterestRate, $repaymentFortnightNumber) - 1);

        return $result;
    }
    /**
     * Calculate amount for weekly repayment
     * @param $typeId
     * @param float $loanAmount
     * @param float $monthlyInterestRate
     * @param float $repaymentMonthNumber
     */
    public static function calculateWeeklyRepayment(
        float $loanAmount,
        float $monthlyInterestRate,
        float $repaymentMonthNumber
    ) {
        // FIXME: make correct formula
        $realMonthlyInterestRate = $monthlyInterestRate / 100;
        $repaymentWeekNumber = $repaymentMonthNumber * 4;
        $result = $loanAmount * $realMonthlyInterestRate * \pow(1 + $realMonthlyInterestRate, $repaymentWeekNumber);
        $result = $result / (\pow(1 + $realMonthlyInterestRate, $repaymentWeekNumber) - 1);

        return $result;
    }
}
