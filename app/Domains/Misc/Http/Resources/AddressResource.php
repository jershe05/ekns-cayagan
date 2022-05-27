<?php

namespace App\Domains\Misc\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'zone' => $this->zone,
            'regionId' => $this->region_code,
            'region' => new RegionResource($this->region),
            'provinceId' => $this->province_code,
            'province' => new ProvinceResource($this->province),
            'cityId' => $this->city_code,
            'city' => new CityResource($this->city),
            'barangayId' => $this->barangay_code,
            'barangay' => new BarangayResource($this->barangay),
            'zipCode' => $this->zip_code,
            'lat' => $this->lat,
            'long' => $this->long,
            'updatedAt' => $this->updated_at,
        ];
    }
}
