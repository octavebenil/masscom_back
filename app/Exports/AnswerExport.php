<?php

namespace App\Exports;

use App\Models\Answer;
use App\Models\QuestionOption;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AnswerExport implements FromCollection, WithHeadings, WithMapping
{

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return Collection
     */

    public function collection(): Collection
    {
        return Answer::query()->where('survey_id', $this->id)->get();
    }

    public function headings(): array
    {
        return [
            'User Email/Phone',
            'Question Title',
            'Selected Option',
        ];
    }

    public function map($row): array
    {

        $value_option = null;
        if ($row->question->question_type === 'MCQ') {
            $value_option = strtok(QuestionOption::query()->find(json_decode($row->selected_options, true))->option_text, '_');
        } elseif ($row->question->question_type === 'input') {
            $value_option = json_decode($row->selected_options, true)[array_key_last(json_decode($row->selected_options, true))];
        } elseif ($row->question->question_type === 'MAQ') {
            foreach (json_decode($row->selected_options, true) as $a) {
                {
                    $value_option .= strtok(QuestionOption::query()->find(json_decode($a, true))->option_text, '_');
                }
            }
        }
        return [
            $row->user->email,
            $row->question->question_text,
            $value_option
        ];
    }
}
