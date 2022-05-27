<?php

namespace App\Domains\Voter\Http\Resources;

use App\Domains\Misc\Http\Resources\AddressResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VoterResource extends JsonResource
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
            'phone' => $this->phone,
            'precinct_id' => $this->precinct_id,
            'precinct' => $this->precinct->name,
            'added_by' => $this->added_by,
            'deleted_at' => $this->deleted_at,
            'address' => new AddressResource($this->whenLoaded('address')),
        ];
    }
}
