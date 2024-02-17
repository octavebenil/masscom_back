<?php

namespace App\Observers;

use App\Models\Advertisement;

class AdvertisementObserver
{
    /**
     * Handle the Advertisement "created" event.
     */
    public function created(Advertisement $advertisement): void
    {
        //
    }

    /**
     * Handle the Advertisement "updated" event.
     */
    public function updated(Advertisement $advertisement): void
    {
    }

    /**
     * Handle the Advertisement "deleted" event.
     */
    public function deleted(Advertisement $advertisement): void
    {
        //
    }

    /**
     * Handle the Advertisement "restored" event.
     */
    public function restored(Advertisement $advertisement): void
    {
        //
    }

    /**
     * Handle the Advertisement "force deleted" event.
     */
    public function forceDeleted(Advertisement $advertisement): void
    {
        //
    }
}
