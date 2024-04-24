<?php

namespace App\Observers;

use App\Models\Advertisement;
use App\Models\User;
use App\Notifications\CampaignFinished;

class ParrainageObserver
{
    /**
     * Handle the Advertisement "updated" event.
     */
    public function updated(User $user): void
    {
        if(!empty($user->code_parrain)){

            $parrain = User::query()->where('code_affiliation', $user->code_parrain)->first();

            if($parrain){
                $count_childs = User::query()->where('code_parrain', $user->code_parrain)
                    ->where('comptabilise', 0)
                    ->count();

                if($count_childs >= $parrain->objectif){
                    //objectif atteinte
                    //on remet a zero
                    User::query()->where('code_parrain', $user->code_parrain)
                        ->where('comptabilise', 0)
                        ->update(['comptabilise', 1]);


                    //on notifie l'admin
                    $admin = User::whereEmail('admin@masscom.com')->first();
                    $admin?->notify(
                        new CampaignFinished(
                            "L'utilisateur $parrain->email résidant dans le commune de $parrain->commune
                            a atteint son objectif de parrainage. <br/>
                            ID utilisateur : $parrain->id <br/>
                            Téléphone : $parrain->email <br/>
                            Commune: $parrain->commune <br/>
                            Code de parrainage: $parrain->code_affiliation",
                            "admin.parrainages.list"
                        )
                    );

                }
            }
        }
    }
}
