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
    public static function createRepayment(&$bag, $data)
    {
        $validator = Validator::make($data, RepaymentRequest::staticRules(),
            RepaymentRequest::staticMessages());
        if ($validator->fails()) {
            $bag = [
                'message' => 'Validate error',
                'errors' => $validator->errors(),
            ];
            return null;
        }
        $repayment = new Repayment();
        $repayment->setProps($bag);
        if ($repayment->save()) {
            return $repayment;
        }
        $bag = [
            'message' => 'Saving fail',
        ];
        return null;
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
        $loan = Loan::find($request->get('loanId'));
        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }
        return new RepaymentCollection($loan->repayments);
    }
}
