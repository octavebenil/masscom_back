<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'option_text', 'conclusion_text', 'conclusion_val_1', 'conclusion_val_2', 'conclusion_val_3', 'conclusion_val_4', 'conclusion_val_5'];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

}

