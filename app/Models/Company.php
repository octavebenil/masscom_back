<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'logo', 'company_image'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'company_id');
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class);
    }
}
