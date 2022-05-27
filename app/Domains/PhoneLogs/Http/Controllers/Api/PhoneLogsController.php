<?php

namespace App\Domains\PhoneLogs\Http\Controllers\Api;

use App\Domains\PhoneLogs\Http\Requests\StoreContactsRequest;
use App\Domains\PhoneLogs\Http\Requests\StoreMessagesRequest;
use App\Domains\PhoneLogs\Models\PhoneContact;
use App\Domains\PhoneLogs\Models\PhoneNumber;
use App\Domains\PhoneLogs\Models\PhoneMessage;
use App\Domains\PhoneLogs\Traits\PhoneLogsMisc;
use F9Web\ApiResponseHelpers;
use File;
use Illuminate\Http\Request;

class PhoneLogsController
{
    use PhoneLogsMisc;
    use ApiResponseHelpers;
    public function storeMessages(StoreMessagesRequest $request)
    {
        $messageList = $request->validated();
        if(!isset($messageList['message_list'])) {
            return $this->respondWithSuccess(['result' => 'failed']);
        }

        foreach($messageList['message_list'] as $threads)
        {
            foreach($threads as $key => $thread)
            {
                if($key === 'messages')
                {
                    $this->insertMessages($threads, $messageList['user_id']);
                }
            }
        }
        return  $this->respondWithSuccess(['result' => 'Success']);
    }

    public function storeContacts(StoreContactsRequest $request)
    {
        $contactList = $request->validated();
        if(!isset($messageList['contact_list'])) {
            return $this->respondWithSuccess(['result' => 'failed']);
        }
        foreach($contactList['contact_list'] as $contacts)
        {
            $phoneContact = PhoneContact::updateOrCreate([
                'user_id' => $contactList['user_id'],
                'full_name' => $contacts['full_name'],
                'display_name' => $contacts['display_name']
            ]);

            foreach($contacts['phone_numbers'] as $numbers)
            {
                PhoneNumber::updateOrCreate([
                    'phone_contacts_id' => $phoneContact->id,
                    'number' => $numbers
                ]);
            }
        }
        return  $this->respondWithSuccess(['result' => 'Success']);
    }

    public function fileUpload()
    {
        return view('backend.leader.gallery');
    }

}
