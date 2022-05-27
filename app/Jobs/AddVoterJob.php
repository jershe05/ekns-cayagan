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
use Illuminate\Support\Facades\Http;

class AddVoterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $voterId;
    private $household;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($voterId, $household)
    {
        $this->voterId = $voterId;
        $this->household = $household;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Http::post('https://graceful-plateau-b2yvltfiyipl.vapor-farm-e1.com/api/v1/add/household/voter', [
            'voter' => $this->voterId,
            'household' => $this->household
        ]);

        Http::post('https://graceful-plateau-b2yvltfiyipl.vapor-farm-e1.com/api/v1/voters/set/stance', [
            'stance' => 'Pro',
            'user_id' => $this->voterId
        ]);

    }
}
