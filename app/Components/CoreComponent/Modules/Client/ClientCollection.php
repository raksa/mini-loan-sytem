<?php

namespace App\Components\CoreComponent\Modules\Client;

use Illuminate\Http\Resources\Json\ResourceCollection;

/*
 * Author: Raksa Eng
 */
class ClientCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->resource->map(function ($client, $request) {
            return (new ClientResource($client))->toArray($request);
        });
    }
}
