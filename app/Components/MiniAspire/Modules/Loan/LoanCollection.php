<?php

namespace App\Components\MiniAspire\Modules\Loan;

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
        $loans = $this->resource;
        $loansArray = [];
        foreach ($loans as $loan) {
            $array = $loan->toArray();
            $loansArray[] = $array;
        }
        return $loansArray;
    }
}
