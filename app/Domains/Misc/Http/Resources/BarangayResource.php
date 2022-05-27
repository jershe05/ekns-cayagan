<?php

namespace App\Domains\Misc\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BarangayResource extends JsonResource
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
            'psgc' => $this->barangay_code,
            'barangayCode' => $this->barangay_code,
            'name' => $this->barangay_description,
            'cityCode' => $this->city_municipality_code,
            'provinceCode' => $this->province_code,
            'regionCode' => $this->region_code,
        ];
    }
}
