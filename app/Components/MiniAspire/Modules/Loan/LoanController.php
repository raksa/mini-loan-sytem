<?php

namespace App\Components\MiniAspire\Modules\Loan;

use App\Components\MiniAspire\Modules\Repayment\RepaymentController;
use App\Components\MiniAspire\Modules\Repayment\RepaymentFrequency;
use App\Components\MiniAspire\Modules\Client\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Validator;

// TODO: make repository
// TODO: comment some complexity parts
/*
 * Author: Raksa Eng
 */
class LoanController extends Controller
{
    /**
     * Create loan via api
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Components\MiniAspire\Modules\Client\Client:ID $id
     */
    public function apiCreateLoan(Request $request, $id)
    {
        $client = Client::find($id);
        if (!$client) {
            return response()->json([
                "status" => "success",
                "message" => trans("default.client_not_found"),
            ], 404);
        }
        $data = $request->all();
        $validator = Validator::make($data, LoanRequest::staticRules(),
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
        $data[Loan::CLIENT_ID] = $id;
        $loan->setProps($data);
        if ($loan->save() && RepaymentController::generateRepayments($bag, $loan)) {
            DB::commit();
            return response()->json([
                "status" => "success",
                "loan" => new LoanResource($loan->refresh()),
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
     * @param \App\Components\MiniAspire\Modules\Client\Client:ID $id
     */
    public function apiGetLoan(Request $request, $id)
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
            "types" => RepaymentFrequency::toArrayForApi(),
        ], 200);
    }
}
