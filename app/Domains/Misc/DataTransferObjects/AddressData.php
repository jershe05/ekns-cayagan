<?php


namespace App\Domains\Misc\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class AddressData extends DataTransferObject
{
    public ?string $city_code;
    public ?string $barangay_code;
    public ?string $province_code;
    public ?string $region_code;
    public ?int $scope_id;
    public ?int $zone_no;
}
