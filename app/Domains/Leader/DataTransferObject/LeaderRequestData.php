<?php

namespace App\Domains\Leader\DataTransferObject;

use App\Domains\Misc\DataTransferObjects\AddressData;
use Spatie\DataTransferObject\DataTransferObject;

class LeaderRequestData extends DataTransferObject
{
    public ?int $added_by;
    public ?string $first_name;
    public ?string $middle_name;
    public ?string $last_name;
    public ?string $birthday;
    public ?string $gender;
    public ?string $phone;
    public ?AddressData $address;
}
