<?php

namespace App\Components\MiniAspire\Modules\Client;

use App\Components\MiniAspire\Modules\Loan\LoanCollection;
use Illuminate\Http\Resources\Json\Resource;

class ClientResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $client = $this->resource;
        $array = $client->toArray();
        $array['loans'] = (new LoanCollection($client->loans))->toArray($request);
        return $array;
    }
}
