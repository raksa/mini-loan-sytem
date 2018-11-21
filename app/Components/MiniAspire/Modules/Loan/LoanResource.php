<?php

namespace App\Components\MiniAspire\Modules\Loan;

use App\Components\MiniAspire\Modules\Repayment\RepaymentCollection;
use App\Components\MiniAspire\Modules\User\UserResource;
use Illuminate\Http\Resources\Json\Resource;

class LoanResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $loan = $this->resource;
        $array = $loan->toArray();
        $array['repayments'] = (new RepaymentCollection($loan->repayments))->toArray($request);
        return $array;
    }
}
