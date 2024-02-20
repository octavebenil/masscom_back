<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurveyResource;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use JsonException;
use Pcsaini\ResponseBuilder\ResponseBuilder;

class SurveyController extends Controller
{
    public function index(): Response
    {
        $questions = Survey::query()
                           ->where([
                               ['is_active', true],
                               ['is_closed', false]
                           ])
                           ->whereColumn('max_participants', '>=', 'current_participations')
                           ->latest()
                           ->first();

        if (!$questions) {
            $this->response->questions = [
                "max_part" => 0
            ];
        } else {
            $this->response->questions = new SurveyResource($questions);
        }

        return ResponseBuilder::success($this->response);
    }

    public function submit(Request $request): Response
    {
        $survey = Survey::query()->where("is_active", true)->first();
        $data = $request->selectedAnswer;
        $email = $request->email;
        $user = User::query()
                    ->create([
                        'email'     => $email,
                        'survey_id' => $survey->id
                    ]);

        return $this->extracted($data, $user);
    }

    /**
     * @param mixed $data
     * @param $user
     * @return Response
     * @throws JsonException
     */
    public function extracted(mixed $data, $user): Response
    {
        $answer = Answer::query();

        $survey = Survey::query()
                        ->where("is_active", true)
                        ->first();

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
            $survey = Survey::query()->where("is_active", true)->first();
            Log::error($survey->is_closed);

            if (array_key_exists('surveyId', $d)) {
                if ($survey->id === $d['surveyId']) {
                    if ($survey->is_closed) {
                        return ResponseBuilder::error("Reached max participant", $this->badRequest);
                    }
                    $user = $user->create(['email' => $d['email'], 'survey_id' => $survey->id]);

                    $this->extracted($d['selectedAnswer'], $user);
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
}

