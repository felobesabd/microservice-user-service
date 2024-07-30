<?php

namespace App\Console\Commands;

use App\Redis\IPubSubPublisher;
use App\Service\IAuthService;
use App\Service\Imp\AuthService;
use Illuminate\Console\Command;

class AppSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel';

    public IPubSubPublisher $pubSubscriber;
    public AuthService $authService;

    public function __construct(
        AuthService $authService,
        IPubSubPublisher $pubSubscriber
    )
    {
        parent::__construct();
        $this->pubSubscriber = $pubSubscriber;
        $this->authService = $authService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscribeList = [
            "user_added_note"
        ];

        $subscribeCallbacks = [];
        $subscribeCallbacks['userAddedNotes'] = function ($message, $pubSub) {
            var_dump('message user', $message);
            $this->authService->updateCount($message->notes_count, $message->user_id);
        };

        $this->pubSubscriber->subscribe($subscribeList, $subscribeCallbacks);
    }
}
