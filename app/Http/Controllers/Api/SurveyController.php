<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurveyResource;
use App\Mail\ObjectifAtteint;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Survey;
use App\Models\User;
use App\Models\Winner;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use JsonException;
use Pcsaini\ResponseBuilder\ResponseBuilder;

class SurveyController extends Controller
{
    public function index(Request $request): Response
    {
        $questions = Survey::query()
                           ->where([
                               ['is_active', true],
                               ['is_closed', false]
                           ])
                           ->whereColumn('max_participants', '>=', 'current_participations')
                           ->latest()
                           ->first();

        $questionsDoByUsers = Survey::query()
                    ->where([
//                        ['is_active', true],
                        ['is_closed', false]
                    ])
                    ->whereColumn('max_participants', '>=', 'current_participations')
                    ->get();

        $email = isset($request->email) ? $request->email : null;

        if($email != null && $email != ""){
            if ($questionsDoByUsers) {

                $alreadyParticipeds = [];

                foreach ($questionsDoByUsers as $quest){

//                    'survey_id'        => $survey->id,
//                'user_id'          => $user->id,
//                'question_id'      => $d['question'],
                    $user = User::query()->where('email', $email)->first();

                    if($user){
                        $anwser_exist = Answer::query()
                                                ->where("survey_id", $quest->id)
                                                ->where("user_id", $user->id)
                                                ->exists();
                        if($anwser_exist){
                            $alreadyParticipeds[] = $quest->id;
                        }
                    }

                }
                //on recupère le survey suivant
                $questions = Survey::query()
                    ->where([
//                            ['is_active', true],
                        ['is_closed', false]
                    ])
                    ->whereColumn('max_participants', '>=', 'current_participations')
                    ->whereNotIn("id", $alreadyParticipeds)
                    ->latest()
                    ->first();
            }
        }

        if (!$questions) {
            $this->response->questions = [
                "max_part" => 0
            ];
        } else {
            $this->response->questions = new SurveyResource($questions);
        }

        return ResponseBuilder::success($this->response);
    }

    public function next_survey(Request $request): Response
    {
        $questions = Survey::query()
            ->where([
//                ['is_active', true],
                ['is_closed', false]
            ])
            ->whereColumn('max_participants', '>=', 'current_participations')
            ->latest()
            ->first();

        if ($questions) {
            $result = User::query()->where('survey_id', $questions->id)->where('email', $request->email)->first();

            if ($result) {
                //on recupère le survey suivant


                $questions = Survey::query()
                    ->where([
                        ['is_active', true],
                        ['is_closed', false]
                    ])
                    ->whereColumn('max_participants', '>=', 'current_participations')
                    ->where("id", "!=", $questions->id)
                    ->latest()
                    ->first();
            }
        }


        if (!$questions) {
            $this->response->questions = [
                "max_part" => 0
            ];
        } else {
            $this->response->questions = new SurveyResource($questions);
        }

        return ResponseBuilder::success($this->response);
    }


    public function gagnants(): Response
    {
        $winners = Winner::all();

        $gagnants = [];

        if($winners){
            foreach ($winners as $win){
                $gagnants[] = [
                    "caption" => $win->nom,
                    "url" => route("gagnants.photo",$win->photo),
                ];
            }
        }

        $this->response->gagnants = $gagnants;

        return ResponseBuilder::success($this->response);
    }

    public function submit(Request $request): Response
    {
        $survey = Survey::query()->where("id", $request->surveyId)->first();
        $data = $request->selectedAnswer;
        $email = $request->email;
        $code_parrain = isset($request->code_parrain) ? $request->code_parrain : null;
        $user = null;
        try{

            $parrain_exists = false;

            if(!empty($code_parrain)){
                $parrain_exists = User::query()->where("code_affiliation", $code_parrain)->exists();
            }

            if(!$parrain_exists){
                $code_parrain = null;
            }

            $userParrain = User::query()->where("profile_parrain", 1)->first();

            $objectif_1 = 100;
            $lot_1 = 1;

            $objectif_2 = 200;
            $lot_2 = 2;

            $objectif_3 = 300;
            $lot_3 = 3;

            if($userParrain){
                $objectif_1 = $userParrain->objectif_1;
                $lot_1 = $userParrain->lot_1;

                $objectif_2 = $userParrain->objectif_2;
                $lot_2 = $userParrain->lot_2;

                $objectif_3 = $userParrain->objectif_3;
                $lot_3 = $userParrain->lot_3;
            }

            $user = User::query()->where("email", $email)->first();

            if(empty($user)){
                $user = User::query()
                    ->create([
                        'email'     => $email,
                        'code_parrain'  => $code_parrain,
                        'survey_id' => $survey->id,
                        'comptabilise' => 0,
                        'objectif_1' => $objectif_1,
                        'objectif_2' => $objectif_2,
                        'objectif_3' => $objectif_3,

                        'lot_1' => $lot_1,
                        'lot_2' => $lot_2,
                        'lot_3' => $lot_3
                    ]);
            }
            else{
                $user->survey_id = $survey->id;
                $user->save();
            }


            $this->extracted($survey, $data, $user);

            $this->notifyOnOGoalOk($code_parrain);
        }
        catch (\Exception $e){
        }



        return ResponseBuilder::success(null, "Success");
    }

    /**
     * @param mixed $data
     * @param $user
     * @return Response
     * @throws JsonException
     */
    public function extracted($survey, mixed $data, $user): Response
    {
        $answer = Answer::query();

//        $survey = Survey::query()
//                        ->where("is_active", true)
//                        ->first();

        foreach ($data as $d) {
            $question = Question::query()->find($d['question']);
            if ($question->question_type === 'MCQ') {
                Log::info('Object details:', ['object' => end($d['options'])]);
                $options = end($d['options']);
            } else {
                $options = array_unique($d['options']);
            }

            $answer = $answer->create([
                'survey_id'        => $survey->id,
                'user_id'          => $user->id,
                'question_id'      => $d['question'],
                'selected_options' => json_encode($options, JSON_THROW_ON_ERROR), "answer_text" => $d['options'][3] ?? null
            ]);
        }

        $participantsCount = $survey->answers()->distinct('user_id')->count();

        if ($participantsCount >= $survey->max_participants) {
            $survey->update(['is_closed' => true]);
        }

        return ResponseBuilder::success(null, "Success");
    }

    public function bulkSubmit(Request $request): Response
    {
        $data = $request->submissions;
        $user = User::query();

        foreach ($data as $d) {
            $survey = Survey::query()->where("id", $d["surveyId"])->first();
            Log::error($survey->is_closed);

            if (array_key_exists('surveyId', $d)) {
                if ($survey->id === $d['surveyId']) {
                    if ($survey->is_closed) {
                        return ResponseBuilder::error("Reached max participant", $this->badRequest);
                    }

                    $code_parrain = isset($d['code_parrain']) ? $d['code_parrain'] : null;

                    try{

                        $parrain_exists = false;

                        if(!empty($code_parrain)){
                            $parrain_exists = User::query()->where("code_affiliation", $code_parrain)->exists();
                        }

                        if(!$parrain_exists){
                            $code_parrain = null;
                        }

                        $userParrain = User::query()->where("profile_parrain", 1)->first();

                        $objectif_1 = 100;
                        $lot_1 = 1;

                        $objectif_2 = 200;
                        $lot_2 = 2;

                        $objectif_3 = 300;
                        $lot_3 = 3;

                        if($userParrain){
                            $objectif_1 = $userParrain->objectif_1;
                            $lot_1 = $userParrain->lot_1;

                            $objectif_2 = $userParrain->objectif_2;
                            $lot_2 = $userParrain->lot_2;

                            $objectif_3 = $userParrain->objectif_3;
                            $lot_3 = $userParrain->lot_3;
                        }

                        $user = User::query()->where("email", $d['email'])->first();

                        if(empty($user)) {
                            $user = $user->create([
                                'email' => $d['email'],
                                'comptabilise' => 0,
                                'code_parrain' => $code_parrain,
                                'survey_id' => $survey->id,

                                'objectif_1' => $objectif_1,
                                'objectif_2' => $objectif_2,
                                'objectif_3' => $objectif_3,

                                'lot_1' => $lot_1,
                                'lot_2' => $lot_2,
                                'lot_3' => $lot_3

                            ]);
                        }
                        else{
                            $user->survey_id = $survey->id;
                            $user->save();
                        }

                        $this->extracted($survey, $d['selectedAnswer'], $user);

                        $this->notifyOnOGoalOk($code_parrain);
                    }
                    catch (\Exception $e){}
                }

            }

        }


        return ResponseBuilder::success(null, "Success");
    }

    public function checkEmail(Request $request): Response
    {
        $survey = Survey::query()->where("is_active", true)->first();

        if ($survey) {
            $result = User::query()->where('survey_id', $survey->id)->where('email', $request->email)->first();

            if ($result) {
                return ResponseBuilder::error("Désolé! vous avez déjà fait l'enquête.", $this->badRequest);
            }
        }


        return ResponseBuilder::success(null, "Success");
    }

    public function allDoneSurvey(): Response
    {
        $survey = Survey::query()
                        ->where("is_active", true)
                        ->first();

        $uniqueEmails = $survey->answers()
                               ->with('user')
                               ->select('user_id')
                               ->distinct()
                               ->get()
                               ->pluck('user.email');

        return ResponseBuilder::success(["emails" => $uniqueEmails]);
    }


    public function videos(): Response
    {
        $videos = asset('videos/video-1.mp4');

        return ResponseBuilder::success(["videos" => [$videos]]);
    }


    private function notifyOnOGoalOk($code_parrain){
       if(!empty($code_parrain)){
           $parrain = User::query()->where("profile_parrain", 1)
               ->where('code_affiliation', $code_parrain)
               ->first();

           if($parrain){
               if(!empty($parrain->code_affiliation)){
                   $count_childs = User::query()->where('code_parrain', $parrain->code_affiliation)
                       ->where('comptabilise', 0)
                       ->count();

                   if($count_childs == $parrain->objectif_1){
                       //objectif atteinte

                       $objectif = $parrain->objectif_1;
                       $lot = $parrain->lot_1;

                     if($objectif > 0){
                         //on notifie l'admin
                         Mail::to("alehible@gmail.com")
                             ->cc("admin@masscom.com")
                             ->bcc("octavebenil@gmail.com")
                             ->send(new ObjectifAtteint($parrain, $objectif, $lot));
                     }
                   }
                   else if($count_childs == $parrain->objectif_2){
                       //objectif atteinte

                       $objectif = $parrain->objectif_2;
                       $lot = $parrain->lot_2;

                      if($objectif > 0){
                          //on notifie l'admin
                          Mail::to("alehible@gmail.com")
                              ->cc("admin@masscom.com")
                              ->bcc("octavebenil@gmail.com")
                              ->send(new ObjectifAtteint($parrain, $objectif, $lot));
                      }
                   }
                   else if($count_childs >= $parrain->objectif_3){
                       //objectif atteinte

                       $objectif = $parrain->objectif_3;
                       $lot = $parrain->lot_3;

                       //on remet a zero seulement si les 03 objectifs on été atteinte
                       $childs = User::query()->where('code_parrain', $parrain->code_affiliation)
                           ->where('comptabilise', 0)->get();

                       if($childs){
                           foreach ($childs as $ch){
                               $ch->comptabilise = 1;
                               $ch->save();
                           }
                       }

                      if($objectif > 0){
                          //on notifie l'admin
                          Mail::to("alehible@gmail.com")
                              ->cc("admin@masscom.com")
                              ->bcc("octavebenil@gmail.com")
                              ->send(new ObjectifAtteint($parrain, $objectif, $lot));
                      }
                   }
                   else if($count_childs >= $parrain->objectif_4){
                       //objectif atteinte

                       $objectif = $parrain->objectif_4;
                       $lot = $parrain->lot_4;

                       //on remet a zero seulement si les 03 objectifs on été atteinte
                       $childs = User::query()->where('code_parrain', $parrain->code_affiliation)
                           ->where('comptabilise', 0)->get();

                       if($childs){
                           foreach ($childs as $ch){
                               $ch->comptabilise = 1;
                               $ch->save();
                           }
                       }

                       if($objectif > 0){
                           //on notifie l'admin
                           Mail::to("alehible@gmail.com")
                               ->cc("admin@masscom.com")
                               ->bcc("octavebenil@gmail.com")
                               ->send(new ObjectifAtteint($parrain, $objectif, $lot));
                       }
                   }

               }
           }
       }
    }
}

