<?php

namespace App\Components\MiniAspire\Modules\Repayment;

use App\Components\MiniAspire\Modules\Client\ClientResource;
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
        $array['client_id'] = $repayment->loan->client->getId();
        return $array;
    }
}
