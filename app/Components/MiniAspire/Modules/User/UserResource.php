<?php

namespace App\Components\MiniAspire\Modules\User;

use App\Components\MiniAspire\Modules\Loan\LoanCollection;
use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this->resource;
        $array = $user->toArray();
        $array['loans'] = (new LoanCollection($user->loans))->toArray($request);
        return $array;
    }
}
