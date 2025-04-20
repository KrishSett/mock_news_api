<?php

namespace App\Jobs;

use App\Notifications\SubscriptionEmailNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendSubscriptionNotification implements ShouldQueue
{
    use Batchable, Queueable;

    public $subscriber;
    public $data;

    /**
     * Create a new job instance.
     */
    public function __construct($subscriber, $data)
    {
        $this->subscriber = $subscriber;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->subscriber->notify(new SubscriptionEmailNotification($this->data));
    }
}
