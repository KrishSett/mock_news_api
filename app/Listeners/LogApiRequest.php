<?php

namespace App\Listeners;

use App\Events\ApiRequestStarted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use function Illuminate\Log\log;

class LogApiRequest
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
    public function handle(ApiRequestStarted $event): void
    {
        $method = $event->request->method();
        $path = $event->request->getPathInfo();
        $logMessage = 'Input - ' . $path . ' | Method: ' . $method . ' | ';

        if ($method === 'GET') {
            $params = $event->request->query();
            $logMessage .= 'Query Params: ' . json_encode($params);
        } elseif (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $params = $event->request->all();
            $logMessage .= 'Request Params: ' . json_encode($params);
        } else {
            $logMessage .= 'No input parameters to log for this method.';
        }

        log()->channel('apilog')->info($logMessage);
    }
}
