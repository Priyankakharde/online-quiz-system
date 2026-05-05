<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Quiz extends Model
{
    protected $fillable = [
        'title',
        'category_id',
        'exam_date',
        'start_time', // ✅ NEW (IMPORTANT FIX)
        'duration',
        'description',
    ];

    protected $casts = [
        'exam_date' => 'datetime',
        'start_time' => 'datetime',
    ];

    /*
    |------------------------------------------------------------------
    | 🔗 RELATIONSHIPS
    |------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /*
    |------------------------------------------------------------------
    | ⏳ TIME HANDLING (FIXED)
    |------------------------------------------------------------------
    */

    // ✅ Unified start time
    public function startTime()
    {
        return $this->start_time
            ? Carbon::parse($this->start_time)
            : ($this->exam_date ? Carbon::parse($this->exam_date) : null);
    }

    public function endTime()
    {
        return $this->startTime()
            ? $this->startTime()->copy()->addMinutes($this->duration ?? 0)
            : null;
    }

    public function timeRemaining()
    {
        if (!$this->isOngoing()) return null;

        return now()->diffInSeconds($this->endTime(), false);
    }

    /*
    |------------------------------------------------------------------
    | 🧠 STATUS LOGIC
    |------------------------------------------------------------------
    */

    public function isUpcoming()
    {
        return $this->startTime() && now()->lt($this->startTime());
    }

    public function isOngoing()
    {
        return $this->startTime() && $this->endTime()
            && now()->between($this->startTime(), $this->endTime());
    }

    public function isCompleted()
    {
        return $this->endTime() && now()->gt($this->endTime());
    }

    public function isDraft()
    {
        return !$this->startTime();
    }

    /*
    |------------------------------------------------------------------
    | 🎯 AUTO STATUS (VERY USEFUL)
    |------------------------------------------------------------------
    */

    public function getStatusAttribute()
    {
        if ($this->isDraft()) return 'Draft';
        if ($this->isUpcoming()) return 'Upcoming';
        if ($this->isOngoing()) return 'Ongoing';
        if ($this->isCompleted()) return 'Completed';

        return 'Unknown';
    }

    /*
    |------------------------------------------------------------------
    | 🎨 STATUS COLOR
    |------------------------------------------------------------------
    */

    public function statusColor()
    {
        return match ($this->status) {
            'Draft' => 'bg-gray-500',
            'Upcoming' => 'bg-yellow-500',
            'Ongoing' => 'bg-green-500',
            'Completed' => 'bg-gray-400',
            default => 'bg-gray-300',
        };
    }

    /*
    |------------------------------------------------------------------
    | 🔐 ACCESS CONTROL
    |------------------------------------------------------------------
    */

    public function canStart()
    {
        return $this->isOngoing();
    }

    public function hasEnded()
    {
        return $this->isCompleted();
    }

    /*
    |------------------------------------------------------------------
    | 📊 ANALYTICS
    |------------------------------------------------------------------
    */

    public function totalQuestions()
    {
        return $this->subjects->sum(fn($s) => $s->questions->count());
    }

    public function totalAttempts()
    {
        return $this->results()->count();
    }

    public function averageScore()
    {
        return round($this->results()->avg('percentage') ?? 0, 2);
    }

    public function highestScore()
    {
        return $this->results()->max('percentage') ?? 0;
    }

    /*
    |------------------------------------------------------------------
    | 👤 USER HELPERS
    |------------------------------------------------------------------
    */

    public function isAttemptedByUser($userId)
    {
        return $this->results()
            ->where('user_id', $userId)
            ->exists();
    }

    public function userScore($userId)
    {
        return $this->results()
            ->where('user_id', $userId)
            ->value('score');
    }

    /*
    |------------------------------------------------------------------
    | 🧾 FORMATTERS
    |------------------------------------------------------------------
    */

    public function formattedDate()
    {
        return $this->startTime()
            ? $this->startTime()->format('d M Y h:i A')
            : 'Not set';
    }

    public function durationText()
    {
        return ($this->duration ?? 0) . ' mins';
    }

    /*
    |------------------------------------------------------------------
    | 🔍 SCOPES (ADVANCED)
    |------------------------------------------------------------------
    */

    public function scopeOngoing($query)
    {
        return $query->get()->filter->isOngoing();
    }

    public function scopeUpcoming($query)
    {
        return $query->get()->filter->isUpcoming();
    }

    public function scopeCompleted($query)
    {
        return $query->get()->filter->isCompleted();
    }
}