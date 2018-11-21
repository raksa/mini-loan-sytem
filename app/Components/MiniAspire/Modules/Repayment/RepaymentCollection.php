<?php

namespace App\Components\MiniAspire\Modules\Repayment;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RepaymentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->map(function ($repayment, $request) {
            return (new RepaymentResource($repayment))->toArray($request);
        });
    }
}
