<?php

namespace App\Domains\Auth\Http\Resources;

use App\Domains\Misc\Http\Resources\AddressResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'username' => $this->phone,
            'added_by' => $this->added_by,
        ];
    }
}
