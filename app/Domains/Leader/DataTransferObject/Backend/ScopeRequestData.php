<?php


namespace App\Domains\Leader\DataTransferObject\Backend;

use Spatie\DataTransferObject\DataTransferObject;

class ScopeRequestData extends DataTransferObject
{
    public ?int $scope_id;
}
