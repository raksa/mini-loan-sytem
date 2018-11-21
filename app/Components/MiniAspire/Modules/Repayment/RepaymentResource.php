<?php

namespace App\Components\MiniAspire\Modules\Repayment;

use Illuminate\Http\Resources\Json\Resource;

class RepaymentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $repayment = $this->resource;
        $array = $repayment->toArray();
        $array['user_id'] = $repayment->loan->user->getId();
        return $array;
    }
}
