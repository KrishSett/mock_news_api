<?php

namespace App\Observers;

use App\Jobs\NotifySubscribers;
use App\Models\API\Content;

class ContentObserver
{
    /**
     * Handle the Content "created" event.
     */
    public function created(Content $content): void
    {
        NotifySubscribers::dispatch($content)->delay(now()->addMinutes(2));
    }

    /**
     * Handle the Content "updated" event.
     */
    public function updated(Content $content): void
    {
        //
    }

    /**
     * Handle the Content "deleted" event.
     */
    public function deleted(Content $content): void
    {
        //
    }

    /**
     * Handle the Content "restored" event.
     */
    public function restored(Content $content): void
    {
        //
    }

    /**
     * Handle the Content "force deleted" event.
     */
    public function forceDeleted(Content $content): void
    {
        //
    }
}
