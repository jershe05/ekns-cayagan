<?php

namespace App\Domains\Misc\Http\Resources;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BarangayResourceCollection extends ResourceCollection
{
    public $collects = BarangayResource::class;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['data' => $this->collection];
    }
}
