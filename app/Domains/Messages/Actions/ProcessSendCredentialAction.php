<?php
namespace App\Domains\Messages\Actions;

use App\Domains\Leader\Models\Leader;
use App\Domains\Messages\Models\Message;
use App\Domains\Messages\Models\MessageRecipient;
use App\Jobs\SendMessageJob;

class ProcessSendCredentialAction
{
    /**
     * @param BettingRound $bettingRound
     * @return null
     */
    public function __invoke($sender, $content, $leader)
    {
        $message = Message::create([
            'message' => $content,
            'scope' => 'account'
        ]);

        $messageRecipient = MessageRecipient::create([
            'message_id' => $message->id,
            'leader_id' => $leader->id
        ]);
        SendMessageJob::dispatch($sender, $leader->user, $message->message, $messageRecipient)->onQueue('messaging');

    }
}
