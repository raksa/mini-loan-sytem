<?php

namespace App\Components\MiniAspire\Modules\Loan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
                "errors" => $validator->errors(),
            ], 400);
        }
        $loan = new Loan();
        $loan->setProps([
            Loan::AMOUNT => $request->get(Loan::AMOUNT),
        ]);
        if ($loan->save()) {
            return response()->json([
                "status" => "success",
            ], 200);
        }
        return response()->json([
            "status" => "error",
            "error" => trans('default.save_loan_fail'),
        ], 500);
    }
}
