<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
    |------------------------------------------------------------------
    | 🧾 Fillable Fields
    |------------------------------------------------------------------
    */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // admin / student
    ];

    /*
    |------------------------------------------------------------------
    | 🔒 Hidden Fields
    |------------------------------------------------------------------
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |------------------------------------------------------------------
    | 🎯 Casting
    |------------------------------------------------------------------
    */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
    |------------------------------------------------------------------
    | 🔄 Auto Hash Password
    |------------------------------------------------------------------
    */
    public function setPasswordAttribute($value)
    {
        if ($value && !str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /*
    |------------------------------------------------------------------
    | 🔗 Relationships
    |------------------------------------------------------------------
    */

    // User has many results
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    // 🔥 Quizzes attempted by user (through results)
    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'results')
            ->withPivot(['score', 'total', 'percentage'])
            ->withTimestamps();
    }

    /*
    |------------------------------------------------------------------
    | 📊 Helper Methods
    |------------------------------------------------------------------
    */

    // Total attempts
    public function totalAttempts()
    {
        return $this->results()->count();
    }

    // Average percentage
    public function averageScore()
    {
        if ($this->results->isEmpty()) return 0;

        return round($this->results->avg('percentage'), 2);
    }

    // Highest percentage
    public function highestScore()
    {
        if ($this->results->isEmpty()) return 0;

        return round($this->results->max('percentage'), 2);
    }

    // Passed exams count
    public function passedCount()
    {
        return $this->results->where('percentage', '>=', 40)->count();
    }

    // Failed exams count
    public function failedCount()
    {
        return $this->results->where('percentage', '<', 40)->count();
    }

    // Recent attempts (last 5)
    public function recentResults()
    {
        return $this->results()->latest()->take(5)->get();
    }

    // 🎯 Pass / Fail check
    public function isPassed($quizId)
    {
        $result = $this->results->where('quiz_id', $quizId)->first();

        return $result ? $result->percentage >= 40 : false;
    }

    /*
    |------------------------------------------------------------------
    | 🏆 Ranking (Leaderboard Ready)
    |------------------------------------------------------------------
    */
    public function rank()
    {
        return self::withCount('results')
            ->get()
            ->sortByDesc(fn($user) => $user->averageScore())
            ->pluck('id')
            ->search($this->id) + 1;
    }

    /*
    |------------------------------------------------------------------
    | 🎯 Role Helpers
    |------------------------------------------------------------------
    */

    public function isAdmin()
    {
        return ($this->role ?? '') === 'admin';
    }

    public function isStudent()
    {
        return ($this->role ?? '') === 'student';
    }

    /*
    |------------------------------------------------------------------
    | 🔍 Scopes (Search)
    |------------------------------------------------------------------
    */

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        return $query;
    }
}