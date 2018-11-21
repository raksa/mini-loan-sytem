<?php

namespace App\Components\MiniAspire\Modules\Repayment;

use App\Components\MiniAspire\Modules\Loan\Loan;
use App\Helpers\LoanCalculator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Validator;

// TODO: comment some complexity parts
/*
 * Author: Raksa Eng
 */
class RepaymentController extends Controller
{
    /**
     * Generate repayments
     *
     * @param \Illuminate\Http\Request $request
     */
    public static function generateRepayments(&$bag, Loan $loan)
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
        while ($dueDate->lessThanOrEqualTo($endDate)) {
            if (RepaymentFrequency::isMonthly($frequencyType)) {
                $dueDate = $dueDate->copy()->addMonth(1);
            } else if (RepaymentFrequency::isFortnightly($frequencyType)) {
                $dueDate = $dueDate->copy()->addWeek(2);
            } else {
                $dueDate = $dueDate->copy()->addWeek(1);
            }
            if (!static::createRepayment($bag, [
                Repayment::LOAN_ID => $loan->getId(),
                Repayment::AMOUNT => $amount,
                Repayment::PAYMENT_STATUS => RepaymentStatus::UNPAID["id"],
                Repayment::DUE_DATE => $dueDate,
                Repayment::DATE_OF_PAYMENT => null,
                Repayment::REMARKS => null,
            ])) {
                return false;
            }
        }
        return true;
    }
    /**
     * Create repayment
     *
     * @param \Illuminate\Http\Request $request
     */
    private static function createRepayment(&$bag, $data)
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
     * Pay for repayment
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Components\MiniAspire\Modules\Repayment\Repayment::ID $id
     */
    public function apiPay(Request $request, $id)
    {
        DB::beginTransaction();
        $repayment = Repayment::lockForUpdate()->find($id);
        if (!$repayment) {
            DB::rollBack();
            return response()->json([
                "status" => "error",
                "message" => trans("default.repayment_not_found"),
            ], 404);
        }
        if (RepaymentStatus::isPaid($repayment->getPaymentStatusId())) {
            DB::rollBack();
            return response()->json([
                "status" => "error",
                "message" => trans("default.repayment_already_paid"),
            ], 400);
        }
        $repayment->setPaymentStatusId(RepaymentStatus::PAID["id"]);
        try {
            if ($repayment->save()) {
                DB::commit();
                return response()->json([
                    "status" => "success",
                    "repayment" => new RepaymentResource($repayment),
                ], 200);
            }
        } catch (\Exception $e) {}
        DB::rollBack();
        return response()->json([
            "status" => "error",
            "error" => trans("default.saving_fail"),
        ], 500);
    }

    /**
     * Get repayment list
     * Get repayment if repayment"s id is specified
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Components\MiniAspire\Modules\Repayment\Repayment::ID $id
     */
    public function apiGetRepayment(Request $request, $id)
    {
        $repayment = Repayment::find($id);
        if (!$repayment) {
            return response()->json(['message' => trans('default.repayment_not_found')], 404);
        }
        return new RepaymentResource($repayment);
    }
}
