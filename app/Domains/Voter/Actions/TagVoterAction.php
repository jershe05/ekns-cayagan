<?php
namespace App\Domains\Voter\Actions;

use App\Domains\Auth\Models\User;
use App\Domains\Leader\Models\Leader;
use App\Domains\Messages\Models\Message;
use App\Domains\Messages\Models\MessageRecipient;
use App\Jobs\AddVoterJob;
use App\Jobs\SendMessageJob;

class TagVoterAction
{
    /**
     * @param BettingRound $bettingRound
     * @return null
     */
    public function __invoke($voterId, $household)
    {
        AddVoterJob::dispatch($voterId, $household);
    }
}
