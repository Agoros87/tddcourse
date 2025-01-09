<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory;

    public function getReadableDuration(): string
    {
        return Str::of($this->duration_in_min)->append('min');


    }

    public function alreadyWatchedByCurrentUser(): bool
    {
        return (bool) auth()->user()->watchedVideos()->where('video_id', $this->id)->count();
    }


    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }


}
