<?php

namespace App\Components\MiniAspire\Modules\Repayment;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Validator;

/*
 * Author: Raksa Eng
 */
class RepaymentController extends Controller
{
    /**
     * Create repayment via api
     *
     * @param \Illuminate\Http\Request $request
     */
    public function apiCreateRepayment(Request $request)
    {
        $validator = Validator::make($request->all(), RepaymentRequest::staticRules(),
            RepaymentRequest::staticMessages());
        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "errors" => $validator->errors(),
            ], 400);
        }
        $repayment = new Repayment();
        $repayment->setProps([
            Repayment::AMOUNT => $request->get(Repayment::AMOUNT),
        ]);
        if ($repayment->save()) {
            return response()->json([
                "status" => "success",
            ], 200);
        }
        return response()->json([
            "status" => "error",
            "error" => trans('default.save_repayment_fail'),
        ], 500);
    }

    /**
     * Get repayment list
     * Get repayment if repayment's id is specified
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Components\MiniAspire\Modules\Repayment\Repayment::ID $id
     */
    public function apiGetRepayment(Request $request, $id = null)
    {
        $repayment = Repayment::find($id);
        if ($repayment) {
            return new RepaymentResource($repayment);
        }
        return new RepaymentCollection(Repayment::filterRepayment([
            'perPage' => $request->get('perPage') ?? 10,
        ]));
    }
}
