<?php
namespace App\Domains\Messages\Actions;

use App\Domains\Leader\Models\Leader;
use App\Domains\Messages\Models\Message;
use App\Domains\Messages\Models\MessageRecipient;
use App\Jobs\SendMessageJob;

class ProcessSendOTP
{
    /**
     * @param BettingRound $bettingRound
     * @return null
     */
    public function __invoke($sender, $content, $voter)
    {
        SendMessageJob::dispatch($sender, $voter, $content)->onQueue('messaging');
    }
}
