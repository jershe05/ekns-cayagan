<?php

namespace App\Domains\Misc\Actions;

use App\Domains\Misc\DataTransferObjects\AddressData;
use App\Domains\Misc\Models\Address;

class StoreModelAddressAction
{
    public function __invoke(AddressData $data, $model) : Address
    {
        return $model->address()->create([
            'island_id' => $data->scope_id,
            'zone_no' => $data->zone_no,
            'region_code' => $data->region_code,
            'city_code' => $data->city_code,
            'province_code' => $data->province_code,
            'barangay_code' => $data->barangay_code,
        ]);
    }
}
