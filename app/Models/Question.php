<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['survey_title', 'question_text', 'question_type', 'questions', 'is_active'];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }


    public function options(): HasMany
    {
        return $this->hasMany(QuestionOption::class);
    }


    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
