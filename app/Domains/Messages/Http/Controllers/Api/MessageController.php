<?php

namespace App\Domains\Messages\Http\Controllers\Api;

use App\Domains\Messages\Http\Requests\GetNewMessageRequest;
use App\Domains\Messages\Models\MessageRecipient;
use Request;
use F9Web\ApiResponseHelpers;
class MessageController
{
    use ApiResponseHelpers;
    public function getNewMessages(GetNewMessageRequest $request)
    {
        $messageRecipients = MessageRecipient::where('leader_id', $request->leader_id)->get();
        $messages = collect();

        foreach($messageRecipients as $messageRecipient)
        {
            $messages->push([
                'message'=>  $messageRecipient->message,
                'message_id' => $messageRecipient->id,
                'status' => $messageRecipient->app_received_status
            ]);
        }

        return  $this->respondCreated([
            'messages' => $messages,
        ]);
    }
}
