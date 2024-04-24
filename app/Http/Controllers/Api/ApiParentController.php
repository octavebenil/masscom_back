<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurveyResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Survey;
use App\Models\User;
use App\Models\Winner;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use JsonException;
use Pcsaini\ResponseBuilder\ResponseBuilder;

class ApiParentController extends Controller
{
    public function nextCode(): Response
    {
        $users = User::all();

        $exists_code_affiliation = [];

        if($users){
            foreach ($users as $user){
                if(!in_array($user->code_affiliation, $exists_code_affiliation) && !empty($user->code_affiliation)){
                    $exists_code_affiliation[] = $user->code_affiliation;
                }
            }
        }

        $code_affiliation = rand(1000, 9999);

        $max_try = 500;
        $iter = 0;

        while (in_array($code_affiliation, $exists_code_affiliation)  && $iter < $max_try){
            $code_affiliation = rand(1000, 9999);
            $iter++;
        }

        $this->response->code_parrain = $code_affiliation;

        return ResponseBuilder::success($this->response);
    }

    public function submit(Request $request): Response
    {
        $survey = Survey::query()->where("is_active", true)->first();

        $email = $request->email;
        $commune = $request->commune;
        $code_parrain = $request->code_parrain;

        $user = User::query()->where("email", $email)->first();

        if($user){
            $user->commune = $commune;
            $user->code_affiliation = $code_parrain;
            $user->profile_parrain = 1;
            $user->save();
        }
        else{
            $user = User::query()
                ->create([
                    'email'     => $email,
                    'commune'     => $commune,
                    'code_affiliation'  => $code_parrain,
                    'profile_parrain'  => 1,
                    'survey_id' => $survey->id,
                ]);
        }

//        $this->notifyOnOGoalOk();

        return ResponseBuilder::success(null, "Success");
    }

    public function bulkSubmit(Request $request): Response
    {
        $data = $request->submissions;

        $survey = Survey::query()->where("is_active", true)->first();

        foreach ($data as $d) {

            $user = User::query()->where("email", $d["email"])->first();

            if($user){
                $user->commune = $d["commune"];
                $user->code_affiliation = $d["code_parrain"];
                $user->save();
            }
            else{
                $user = User::query()
                    ->create([
                        'email'     => $d["email"],
                        'commune'     => $d["commune"],
                        'code_affiliation'  => $d["code_parrain"],
                        'profile_parrain'  => 1,
                        'survey_id' => $survey->id
                    ]);
            }

        }

//        $this->notifyOnOGoalOk();


        return ResponseBuilder::success(null, "Success");
    }

    private function notifyOnOGoalOk(){
        $users = User::query()->where("profile_parrain", 1)->get();

        if($users){
            foreach ($users as $user){
                if(!empty($user->code_affiliation)){
                    $count_childs = User::query()->where('code_parrain', $user->code_affiliation)
                        ->where('comptabilise', 0)
                        ->count();

                    if($count_childs >= $user->objectif){
                        //objectif atteinte
                        //on notifie l'admin


                        //on remet a zero
                        User::query()->where('code_parrain', $user->code_affiliation)
                            ->where('comptabilise', 0)
                            ->update(['comptabilise', 1]);

                    }

                }
            }
        }
    }
}

