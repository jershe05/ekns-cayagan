<?php

namespace App\Jobs;

use App\Domains\Auth\Models\User;
use App\Services\TextService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $textService;
    private $recipient;
    private $message;
    private $client;
    private $sender;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sender, User $recipient, $message, $client = null)
    {
        $this->recipient = $recipient;
        $this->message = $message;
        $this->client = $client;
        $this->sender = $sender;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->textService = new TextService($this->sender, $this->message, $this->recipient->phone,  $this->client);
        $this->textService->send();
    }

    private function formatPhoneNumber($phone)
    {
        if(strlen($phone) === 10) {
            return '0' . $phone;
        }
    }
}
