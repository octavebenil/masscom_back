<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SurveyDataTable;
use App\Exports\AnswerExport;
use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Company;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Survey;
use App\Models\Winner;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Pcsaini\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SurveyController extends Controller
{
    public function index(SurveyDataTable $dataTable)
    {
        return $dataTable->render('admin.surveys.index');
    }

    public function edit($id): Factory|\Illuminate\Foundation\Application|View|Application|RedirectResponse
    {
        try {
            $survey = Survey::query()->find($id);
            if (!$survey) {
                return back()->with(['error' => __('admin.survey_not_found')]);
            }

            $surveys = Survey::all();
            $selectedSurveys = [];
            $companies = Company::all();
            return view('admin.surveys.create', compact('surveys', 'survey', 'selectedSurveys', 'companies'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }

    public function save(Request $request)
    {
        try {

            if ($request->get('id')) {
                $surveyModel = Survey::query()->find($request->get('id'));

                if (!$surveyModel) {
                    return back()->with(['error' => __('admin.survey_not_found')]);
                }
            }

            $validator = Validator::make($request->all(), [

            ]);

            if ($validator->fails()) {
                return back()->with(['error' => $validator->errors()->first()])->withInput($request->all());
            }

            $data = $request->all();



            if ($request->hasFile('company_logo')) {
                $extension = $request->file('company_logo')->getClientOriginalExtension();
                $name = $request->file('company_logo')->getClientOriginalName();
                $name = Str::slug(explode('.', $name)[0]) . '-' . time() . '.' . $extension;
                $path = 'uploads/surveys/company_logos';
                $data['company_logo'] = $request->file('company_logo')->storePubliclyAs($path, $name);
            }

            if (isset($surveyModel)) {
                $data['max_participants'] = $request->max_participants;
                $surveyModel->update($data);
                $message = __('admin.survey_updated');
            } else {
                $survey = Survey::query()->create(['title' => $request->survey_title, 'max_participants' => $request->max_participants, 'company_id' => $request->company]);

                foreach ($data['questions'] as $surveyData) {
                    $question = $survey->questions()->create([
                        'question_text' => $surveyData['text'],
                        'question_type' => $surveyData['type'],
                    ]);

                    if (in_array($surveyData['type'], ['MCQ', 'MAQ'])) {
                        foreach ($surveyData['options'] as $option) {
                            $question->options()->create(['option_text' => $option . '_' . $question->id]);
                        }
                    }

                }

                $message = __('admin.survey_created');
            }

            return to_route('admin.surveys.list')->with(['success' => $message]);
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')])->withInput($request->all());
        }

    }


    public function winners(Request $request)
    {
        $surveyModel = Survey::query()->find($request->get('survey_id'));

        if (!$surveyModel) {
            return back()->with(['error' => __('admin.survey_not_found')]);
        }

        $data = $request->all();

        $extension = $request->file('photo')->getClientOriginalExtension();
        $name = $request->file('photo')->getClientOriginalName();
        $name = Str::slug(explode('.', $name)[0]) . '-' . time() . '.' . $extension;
        $path = 'uploads/surveys/winners';

        $wins["survey_id"] = $surveyModel->id;
        $wins["path"] = $path;
        $wins['photo'] = $request->file('photo')->storePubliclyAs($path, $name);

        Winner::query()->create([
            "survey_id" => $wins["survey_id"],
            "path" => $wins["path"],
            "photo" => $wins["photo"],
            "nom" => $data["nom"],
            "prenoms" => isset($data["prenoms"]) ? $data["prenoms"] : "",
            "adresse" => $data["adresse"],
            "phone" => $data["phone"],
        ]);

        return back()->with(['success' => "Ajouter avec succès"]);

    }

    public function winners_delete($id): Response|RedirectResponse
    {
        try {
            $winner = Winner::query()->find($id);
            if (!$winner) {
                return back()->with(['error' => "Oups !!! Non trouvé !"]);
            }

            $winner->delete();

            return ResponseBuilder::success(null, __('admin.survey_deleted'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }

    public function create(): Factory|\Illuminate\Foundation\Application|View|Application|RedirectResponse
    {
        try {
            $surveys = Survey::all();
            $selectedSurveys = [];
            $selectedUniversities = [];
            $companies = Company::all();
            return view('admin.surveys.create', compact('companies', 'surveys', 'selectedSurveys', 'selectedUniversities'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }

    public function view($id): Factory|\Illuminate\Foundation\Application|View|Application|RedirectResponse
    {
        try {
            $survey = Survey::query()->find($id);
            $charts = [];
            if (!$survey) {
                return back()->with(['error' => __('admin.survey_not_found')]);
            }

            $questions = Question::query()->where('survey_id', $survey->id)->get();


            foreach ($questions as $question) {

                if ($question->question_type === 'MCQ') {
                    $total_answer = Answer::query()->whereNotNull('id')->where('question_id', $question->id)
                        ->where('survey_id', $survey->id)->count();
                } elseif($question->question_type === 'input') {
                    $total_answer = Answer::query()->whereNotNull('id')->where('question_id', $question->id)
                        ->where('survey_id', $survey->id)->count();
                }else{
                    $total_answer = 0;
                    $answers = Answer::query()->whereNotNull('id')->where('question_id', $question->id)
                        ->where('survey_id', $survey->id)->get();


                    foreach ($answers as $answer) {
                        if (is_array(json_decode($answer->selected_options))) {
                            $total_answer += count(json_decode($answer->selected_options));
                        }
                    }
                }


                if ($total_answer) {
                    $options = QuestionOption::query()->where('question_id', $question->id)->pluck('id');
                    $options_ = QuestionOption::query()->where('question_id', $question->id)->pluck('option_text');
                    $responseCounts = [];
                    $totalResponseCounts = [];


                    foreach ($options as $option) {
                        $responseCount = Answer::query()->where('question_id', $question->id)
                            ->where('survey_id', $survey->id)
                            ->whereJsonContains('selected_options', $option)->count();

                        $responseCounts[] = number_format((float)$responseCount / $total_answer * 100, 2, '.', '');
                        $totalResponseCounts[] = $responseCount;
                    }

                    $modifiedArray = [];

                    foreach ($options_ as $value) {
                        $modifiedValue = strtok($value, '_');
                        $modifiedArray[] = $modifiedValue;
                    }

                    $charts[] = [
                        'question' => $question,
                        'options' => $modifiedArray,
                        'responseCounts' => [
                            "percentage" => $responseCounts,
                            "total" => $totalResponseCounts
                        ],
                        'totalResponseCounts' => $totalResponseCounts,
                    ];
                }
            }

            $winners = Winner::query()->where('survey_id', $survey->id)->get();

            return view('admin.surveys.view', compact('survey', 'charts', 'winners'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }


    public function delete($id): Response|RedirectResponse
    {
        try {
            $survey = Survey::query()->find($id);
            if (!$survey) {
                return back()->with(['error' => __('admin.survey_not_found')]);
            }

            $survey->delete();

            return ResponseBuilder::success(null, __('admin.survey_deleted'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }


    public function updateStatus($id): Response|RedirectResponse
    {
        try {
            $survey = Survey::query()->find($id);
            if (!$survey) {
                return back()->with(['error' => __('admin.survey_not_found')]);
            }


            $surveys = Survey::query();
            $surveys->update(['is_active' => false]);

            $survey->update(['is_active' => !$survey->is_active]);

            return ResponseBuilder::success(null, __('admin.survey_status_updated'));
        } catch (Exception $e) {
            Log::error($e);
            return back()->with(['error' => __('admin.default_error_message')]);
        }
    }

    public function exportChartPdf(Request $request, $id): Response
    {
        $survey = Survey::query()->find($id);

        $pdf = PDF::loadView('admin.pdf.survey_chart', array('survey' => $survey))
            ->setPaper('a4');

        return $pdf->download('survey_questions.pdf');
    }

    public function exportChartCsv(Request $request, $id): BinaryFileResponse
    {
        return Excel::download(new AnswerExport($id), 'answers.xlsx');
    }


}
