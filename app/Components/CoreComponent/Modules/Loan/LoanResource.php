<?php

namespace App\Components\CoreComponent\Modules\Loan;

use App\Components\CoreComponent\Modules\Repayment\RepaymentCollection;
use App\Components\CoreComponent\Modules\Client\ClientResource;
use Illuminate\Http\Resources\Json\Resource;

/*
 * Author: Raksa Eng
 */
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
