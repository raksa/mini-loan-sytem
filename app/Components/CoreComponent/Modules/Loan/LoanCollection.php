<?php

namespace App\Components\CoreComponent\Modules\Loan;

use Illuminate\Http\Resources\Json\ResourceCollection;

class LoanCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->map(function ($loan, $request) {
            return (new LoanResource($loan))->toArray($request);
        });
    }
}
