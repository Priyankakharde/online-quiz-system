<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | 🧾 Mass Assignment
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'quiz_id',
        'name',
    ];

    /*
    |--------------------------------------------------------------------------
    | 🔄 Relationships
    |--------------------------------------------------------------------------
    */

    // 🔗 Subject belongs to Quiz
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    // 🔗 Subject has many Questions
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /*
    |--------------------------------------------------------------------------
    | 🎯 Accessors (Smart Data)
    |--------------------------------------------------------------------------
    */

    // Safe Quiz Title
    public function getQuizTitleAttribute()
    {
        return $this->quiz?->title ?? 'N/A';
    }

    // Questions Count (Auto)
    public function getQuestionsCountAttribute()
    {
        return $this->questions()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | 🔍 Scopes (Advanced Filtering)
    |--------------------------------------------------------------------------
    */

    // Filter by Quiz
    public function scopeByQuiz($query, $quizId)
    {
        return $query->when($quizId, function ($q) use ($quizId) {
            $q->where('quiz_id', $quizId);
        });
    }

    // Search Subject
    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where('name', 'like', "%$search%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ⚡ Helpers
    |--------------------------------------------------------------------------
    */

    // Check if subject has questions
    public function hasQuestions()
    {
        return $this->questions()->exists();
    }

    // Get full display name (UI use)
    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . $this->quiz_title . ')';
    }

    /*
    |--------------------------------------------------------------------------
    | ⚠️ Boot (Auto Fix Data Issues)
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        // Auto trim subject name
        static::saving(function ($subject) {
            $subject->name = trim($subject->name);
        });
    }
}