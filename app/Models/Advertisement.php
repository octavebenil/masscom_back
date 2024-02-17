<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'name',
        'views',
        'max_views',
        'current_views',
        'link'
    ];

    public function scopeNotFinished(Builder $query): void
    {
        $query->whereColumn('current_views', '<', 'max_views');
    }
}
