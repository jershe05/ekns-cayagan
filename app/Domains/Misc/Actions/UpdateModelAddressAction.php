<?php

namespace App\Domains\Misc\Actions;

use App\Domains\Misc\DataTransferObjects\AddressData;
use App\Domains\Misc\Models\Address;

class UpdateModelAddressAction
{
    public function __invoke(AddressData $data, $model) : Address
    {
        $model->address()->updateOrCreate([
            'region_code' => $data->region_code,
            'city_code' => $data->city_municipality_code,
            'province_code' => $data->province_code,
            'barangay_code' => $data->barangay_code,
            'zip_code' => $data->region_code,
        ]);

        return $model->address;
    }
}
