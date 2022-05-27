<?php

namespace App\Domains\Voter\Actions;

use App\Domains\Auth\Models\User;
use App\Domains\Voter\DataTransferObject\VoterRequestData;

class StoreVoterAction
{
    public function __invoke(VoterRequestData $data) : User
    {
        return User::create([
            'added_by' => $data->added_by,
            'first_name' => $data->first_name,
            'middle_name' => $data->middle_name,
            'last_name' => $data->last_name,
            'birthday' => $data->birthday,
            'gender' => $data->gender,
            'phone' => $data->phone,
            'precinct_id' => $data->precinct_id,
        ]);
    }
}
