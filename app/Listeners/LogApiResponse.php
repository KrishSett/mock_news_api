<?php

namespace App\Listeners;

use App\Events\ApiResponseFinished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use function Illuminate\Log\log;

class LogApiResponse
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ApiResponseFinished $event): void
    {
        log()->channel("apilog")->info('Response -' . $event->request->getPathInfo()  . ' | ' . $event->response->getContent());
    }
}
