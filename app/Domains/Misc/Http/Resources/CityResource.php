<?php

namespace App\Domains\Misc\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'psgc' => $this->psgc_code,
            'cityCode'=> $this->city_municipality_code,
            'name' => $this->city_municipality_description,
            'provinceCode' => $this->province_code,
        ];
    }
}
