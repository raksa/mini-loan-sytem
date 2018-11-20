<?php

namespace App\Components\MiniAspire\Modules\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $users = $this->resource;
        $usersArray = [];
        foreach ($users as $user) {
            $array = $user->toArray();
            $usersArray[] = $array;
        }
        return $usersArray;
    }
}
