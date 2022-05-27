<?php
namespace App\Domains\Misc\Traits;

use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Models\Address;
use App\Domains\Misc\Models\Region;

trait HasAddress
{
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

}

