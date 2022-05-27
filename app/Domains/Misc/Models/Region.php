<?php

namespace App\Domains\Misc\Models;

use App\Domains\Analytics\Models\TotalVotersPerLocation;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public function provinces()
    {
        return $this->hasMany(Province::class, 'region_code', 'region_code');
    }

    public function totalVoters()
    {
        return $this->hasMany(TotalVotersPerLocation::class, 'region_code', 'region_code');
    }
}
