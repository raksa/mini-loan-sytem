<?php

namespace App\Components\MiniAspire\Modules\Loan;

use App\Components\MiniAspire\Modules\Repayment\RepaymentController;
use App\Components\MiniAspire\Modules\Repayment\RepaymentFrequency;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Validator;

/*
 * Author: Raksa Eng
 */
class LoanController extends Controller
{
    /**
     * Create loan via api
     *
     * @param \Illuminate\Http\Request $request
     */
    public function apiCreateLoan(Request $request)
    {
        $validator = Validator::make($request->all(), LoanRequest::staticRules(),
            LoanRequest::staticMessages());
        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => trans("default.validation_error"),
                "errors" => $validator->errors(),
            ], 400);
        }
        DB::beginTransaction();
        $loan = new Loan();
        $loan->setProps($request->all());
        if ($loan->save() && RepaymentController::generateRepayments($bag, $loan)) {
            DB::commit();
            return response()->json([
                "status" => "success",
                "loan" => new LoanResource($loan),
            ], 200);
        }
        DB::rollBack();
        return response()->json([
            "status" => "error",
            "message" => trans("default.saving_fail"),
        ], 500);
    }

    /**
     * Get loan list
     * Get loan if loan"s id is specified
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Components\MiniAspire\Modules\Loan\Loan::ID $id
     */
    public function apiGetLoan(Request $request, $id = null)
    {
        $loan = Loan::find($id);
        if ($loan) {
            return new LoanResource($loan);
        }
        return new LoanCollection(Loan::filterLoan([
            "perPage" => $request->get("perPage") ?? 20,
        ]));
    }

    /**
     * Get loan frequency type
     *
     * @param \Illuminate\Http\Request $request
     */
    public function apiGetFreqType(Request $request)
    {
        return response()->json([
            "types" => [
                RepaymentFrequency::MONTHLY['id'] => RepaymentFrequency::MONTHLY['name'],
                RepaymentFrequency::FORTNIGHTLY['id'] => RepaymentFrequency::FORTNIGHTLY['name'],
                RepaymentFrequency::WEEKLY['id'] => RepaymentFrequency::WEEKLY['name'],
            ],
        ], 200);
    }
}
