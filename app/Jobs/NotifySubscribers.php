<?php

namespace App\Jobs;

use App\Notifications\SubscriptionEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotifySubscribers implements ShouldQueue
{
    use Queueable;

    public $content;

    /**
     * Create a new job instance.
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $subscribers = $this->content->topic->subscribers;
        $data = [
            "title" => $this->content->title,
            "body" => $this->content->body
        ];

        $subscribers->each(function ($subscriber) use($data) {
            $subscriber->notify(new SubscriptionEmailNotification($data));
        });
    }
}
