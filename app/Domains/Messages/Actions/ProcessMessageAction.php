<?php
namespace App\Domains\Messages\Actions;

use App\Domains\Auth\Models\User;
use App\Domains\Leader\Models\Leader;
use App\Domains\Messages\Models\Message;
use App\Domains\Messages\Models\MessageRecipient;
use App\Jobs\SendMessageJob;

class ProcessMessageAction
{
    /**
     * @param BettingRound $bettingRound
     * @return null
     */
    public function __invoke($sender, Message $message, $recipients)
    {
        foreach($recipients as $recipient)
        {
            $messageRecipient = MessageRecipient::create([
                'message_id' => $message->id,
                'leader_id' => $recipient
            ]);

            $user = User::where('id', $recipient)->first();
            SendMessageJob::dispatch($sender, $user, $message->message, $messageRecipient)->onQueue('messaging');
        }
    }
}
