<?php

namespace App\Domains\PhoneLogs\Traits;

use App\Domains\PhoneLogs\Models\PhoneMessage;

/**
 *
 */
trait PhoneLogsMisc
{
    private function insertMessages($threads, $userId)
    {
        foreach($threads['messages'] as $key => $message )
        {
            $existingMessage = PhoneMessage::where('user_id', $userId)
                ->where('phone', $message['phone'])
                ->where('message', $message['body'])
                ->where('type', $message['kind'])->first();

            if(!$existingMessage)
            {
                PhoneMessage::updateOrcreate([
                    'user_id' => $userId,
                    'phone' => $message['phone'],
                    'message' => $message['body'],
                    'date' => $message['dateSent'],
                    'type' =>$message['kind'],
                ]);
            }
        }
    }
}
