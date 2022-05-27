<?php

namespace App\Domains\Voter\DataTransferObject;

use App\Domains\Misc\DataTransferObjects\AddressData;
use Spatie\DataTransferObject\DataTransferObject;

class VoterRequestData extends DataTransferObject
{
    public ?int $added_by;
    public ?string $first_name;
    public ?string $middle_name;
    public ?string $last_name;
    public ?string $birthday;
    public ?string $gender;
    public ?string $phone;
    public ?int $precinct_id;
    public ?int $household_id;
    public ?AddressData $address;
}
