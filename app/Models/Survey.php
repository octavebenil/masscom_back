<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active', 'title', 'company_id', 'max_participants', 'is_closed', 'conclusion_text'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function questions(): hasMany
    {
        return $this->hasMany(Question::class, 'survey_id');
    }

    public function answers(): hasMany
    {
        return $this->hasMany(Answer::class, 'survey_id');
    }
}
