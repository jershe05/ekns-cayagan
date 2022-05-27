<?php

namespace App\Domains\Leader\DataTransferObject\Backend;

use App\Domains\Misc\DataTransferObjects\AddressData;
use Spatie\DataTransferObject\DataTransferObject;

class LeaderRequestData extends DataTransferObject
{
    public ?int $user_id;
    public ?int $scope_id;
    public ?int $candidate_id;
    public ?string $type;
    public ?int $organization_id;
    public ?AddressData $address;
}
