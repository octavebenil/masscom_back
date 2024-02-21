<?php

namespace App\Observers;

use App\Models\Advertisement;
use App\Models\User;
use App\Notifications\CampaignFinished;

class AdvertisementObserver
{
    /**
     * Handle the Advertisement "updated" event.
     */
    public function updated(Advertisement $advertisement): void
    {
        if ($advertisement->current_views >= $advertisement->max_views) {
            $admin = User::whereEmail('admin@masscom.com')->first();
            $admin?->notify(
                new CampaignFinished(
                    "La campagne actuelle de publicité est terminée.",
                    "admin.advertisement.list"
                )
            );
        }
    }
}
