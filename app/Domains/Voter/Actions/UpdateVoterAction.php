<?php

namespace App\Domains\Voter\Actions;

use App\Domains\Auth\Models\User;
use App\Domains\Voter\DataTransferObject\VoterRequestData;

class UpdateVoterAction
{
    public function __invoke(VoterRequestData $data, User $user) : User
    {
        $user->added_by = $data->added_by;
        $user->first_name = $data->first_name;
        $user->middle_name = $data->middle_name;
        $user->last_name = $data->last_name;
        $user->birthday = $data->birthday;
        $user->gender = $data->gender;
        $user->phone = $data->phone;
        $user->precinct_id = $data->precinct_id;

        $user->save();
        $user->refresh();

        return $user;
    }
}
