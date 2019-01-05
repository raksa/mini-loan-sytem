<?php

namespace App\Components\CoreComponent\Modules\Loan;

use App\Components\CoreComponent\Modules\Client\Client;
use App\Components\CoreComponent\Modules\Repayment\RepaymentFrequency;
use App\Components\CoreComponent\Modules\Repayment\RepaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Validator;

// TODO: make update and delete options

/*
 * Author: Raksa Eng
 */
class LoanController extends Controller
{
    private $repository;

    public function __construct(LoanRepository $loanRepository)
    {
        $this->repository = $loanRepository;
    }

    /**
     * Create loan via api
     *
     * @param \Illuminate\Http\Request $request
     */
    public function apiCreateLoan(Request $request)
    {
        $client = Client::active()->find($request->get('clientId'));
        if (!$client) {
            return response()->json([
                "status" => "error",
                "message" => trans("default.client_not_found"),
            ], 404);
        }
        $data = $request->except('clientId');
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
        $loan = $this->repository->createLoan($bag, $client, $data);
        // use repayment repository to generate repayment base on loan
        $repaymentRepository = new RepaymentRepository();
        if ($loan && $repaymentRepository->generateRepayments($bag, $loan)) {
            DB::commit();
            return response()->json([
                "status" => "success",
                "loan" => new LoanResource($loan->refresh()),
            ], 200);
        }
        DB::rollBack();
        return response()->json([
            "status" => "error",
            "message" => $bag['message'],
        ], 500);
    }

    /**
     * Get loan list
     * Get loan if loan"s id is specified
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Components\CoreComponent\Modules\Loan\Loan:ID $id
     */
    public function apiGetLoan(Request $request, $id = null)
    {
        if (\is_null($id)) {
            $data = [
                "perPage" => $request->get("perPage") ?? 20,
            ];
            if ($request->has('clientId')) {
                $client = Client::active()->find($request->get('clientId'));
                if (!$client) {
                    return response()->json(['message' => trans("default.client_not_found")], 404);
                } else {
                    $data["client"] = $client;
                }
            }
            return new LoanCollection($this->repository->filterLoan($data));
        }
        $loan = Loan::active()->find($id);
        if (!$loan) {
            return response()->json(['message' => trans("default.loan_not_found")], 404);
        }
        if ($loan) {
            return new LoanResource($loan);
        }

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
