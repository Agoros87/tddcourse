<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Course extends Model
{
    use HasFactory;

    public function scopeReleased(Builder $query): Builder
    {
        return $query->whereNotNull('released_at');
    }
}
