<?php

namespace App\Jobs;

use App\Notifications\SubscriptionEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

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
        $batchJobs  =  [];
        $data       =  [
            "title" => $this->content->title,
            "body"  => $this->content->body
        ];
        
        // Chunk subscribers who belong to this topic
        $this->content->topic->subscribers()
        ->chunk(500, function ($subscribersChunk) use (&$batchJobs, $data) {
            foreach ($subscribersChunk as $subscriber) {
                $batchJobs[] =  new SendSubscriptionNotification($subscriber, $data);
            }
        });

        Bus::batch($batchJobs)->then(function (Batch $batch) {
            logger("All subscriber notifications sent.", ["batch" => $batch]);
        })->catch(function (Batch $batch, Throwable $e) {
            logger("Batch error", ["batch" => $batch, "error"=> $e]);
        })->finally(function (Batch $batch) {
            logger("batch process has been completed", ["batchId" => $batch->id]);
        })->name('Notify Subscribers')->dispatch();
    }
}
