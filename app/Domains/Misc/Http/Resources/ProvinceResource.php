<?php

namespace App\Domains\Misc\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProvinceResource extends JsonResource
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
            'name' => $this->province_description,
            'region' => $this->region_code,
            'provinceCode' => $this->province_code,
        ];
    }
}
