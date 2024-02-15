<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Survey;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;


class DashboardController extends Controller
{
    public function index(): View|\Illuminate\Foundation\Application|Factory|Application
    {
        $survey = Survey::query()->where("is_active", true)->first();
        $charts = [];
        if ($survey){
            $questions = Question::query()->where('question_type','!=',  'input')->where('survey_id', $survey->id)->get();


            foreach ($questions as $question) {
                $options = QuestionOption::query()->where('question_id', $question->id)->pluck('id');
                $options_ = QuestionOption::query()->where('question_id', $question->id)->pluck('option_text');
                $responseCounts = [];

                foreach ($options as $option) {
                    $responseCount = Answer::query()->where('question_id', $question->id)
                        ->where('survey_id', $survey->id)
                        ->whereJsonContains('selected_options', $option)
                        ->count();

                    $responseCounts[] = $responseCount;
                }

                $charts[] = [
                    'question' => $question,
                    'options' => $options_,
                    'responseCounts' => $responseCounts,
                ];
            }
        }



        return view('admin.dashboard.index', compact('charts'));
    }
}
