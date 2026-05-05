<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | 🧾 Mass Assignment
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'subject_id',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
    ];

    /*
    |--------------------------------------------------------------------------
    | 🔄 Relationships
    |--------------------------------------------------------------------------
    */

    // 🔗 Question belongs to Subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // 🔗 Direct access to Quiz (via Subject)
    public function quiz()
    {
        return $this->hasOneThrough(
            Quiz::class,
            Subject::class,
            'id',        // Foreign key on subjects table
            'id',        // Foreign key on quizzes table
            'subject_id',// Local key on questions table
            'quiz_id'    // Local key on subjects table
        );
    }

    /*
    |--------------------------------------------------------------------------
    | 🎯 Accessors (Smart Helpers)
    |--------------------------------------------------------------------------
    */

    // Get Correct Answer Text (A/B/C/D → actual text)
    public function getCorrectOptionTextAttribute()
    {
        return match ($this->correct_answer) {
            'a' => $this->option_a,
            'b' => $this->option_b,
            'c' => $this->option_c,
            'd' => $this->option_d,
            default => null,
        };
    }

    // Format Question Short (for UI)
    public function getShortQuestionAttribute()
    {
        return strlen($this->question) > 60
            ? substr($this->question, 0, 60) . '...'
            : $this->question;
    }

    /*
    |--------------------------------------------------------------------------
    | 🔍 Scopes (Advanced Filtering)
    |--------------------------------------------------------------------------
    */

    // Filter by Quiz
    public function scopeByQuiz($query, $quizId)
    {
        return $query->whereHas('subject', function ($q) use ($quizId) {
            $q->where('quiz_id', $quizId);
        });
    }

    // Search Question
    public function scopeSearch($query, $search)
    {
        return $query->where('question', 'like', "%$search%");
    }

    /*
    |--------------------------------------------------------------------------
    | ⚠️ Boot (Auto Fix Data Issues)
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        // Ensure lowercase correct_answer
        static::saving(function ($question) {
            $question->correct_answer = strtolower($question->correct_answer);
        });
    }
}