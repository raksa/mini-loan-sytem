<?php

namespace App\Components\MiniAspire\Modules\Loan;

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
        return $array;
    }
}
