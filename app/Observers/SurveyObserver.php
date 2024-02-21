<?php

namespace App\Observers;

use App\Models\Survey;
use App\Models\User;
use App\Notifications\CampaignFinished;

class SurveyObserver
{
    /**
     * Handle the Survey "updated" event.
     */
    public function updated(Survey $survey): void
    {
        if ($survey->current_participations >= $survey->max_participants) {
            $admin = User::whereEmail('admin@masscom.com')->first();
            $admin?->notify(new CampaignFinished("Le sondage actuel est terminé.", "admin.surveys.list"));

            $survey->updateQuietly(['is_active' => false, 'is_closed' => true]);
        }
    }
}
