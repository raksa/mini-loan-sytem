<?php

namespace App\Components\CoreComponent\Modules\Repayment;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

// TODO: get repayment of client
// TODO: get over due repayment
// TODO: get up coming

/*
 * Author: Raksa Eng
 */
class RepaymentController extends Controller
{
    private $repository;

    public function __construct(RepaymentRepository $repaymentRepository)
    {
        $this->repository = $repaymentRepository;
    }

    /**
     * Pay for repayment
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Components\CoreComponent\Modules\Repayment\Repayment::id $id
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
        // Ensure already paid
        if (RepaymentStatus::isPaid($repayment->payment_status)) {
            DB::rollBack();
            return response()->json([
                "status" => "error",
                "message" => trans("default.repayment_already_paid"),
            ], 400);
        }
        try {
            $repayment->payment_status = RepaymentStatus::PAID["id"];
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "error" => $e->getMessage(),
            ], 500);
        }
        $repayment->remarks = $request->get("remarks");
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
     * @param \App\Components\CoreComponent\Modules\Repayment\Repayment::id $id
     */
    public function apiGetRepayment(Request $request, $id)
    {
        $repayment = Repayment::active()->find($id);
        if (!$repayment) {
            return response()->json(["message" => trans("default.repayment_not_found")], 404);
        }
        return new RepaymentResource($repayment);
    }
}
