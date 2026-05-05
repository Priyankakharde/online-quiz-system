<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QuizController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 🧾 LIST ALL QUIZZES (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $quizzes = Quiz::latest()->get();
        return view('quizzes.index', compact('quizzes'));
    }

    /*
    |--------------------------------------------------------------------------
    | ➕ CREATE QUIZ (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view('quizzes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
        ]);

        Quiz::create($request->all());

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz created successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | ✏️ EDIT QUIZ (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function edit(Quiz $quiz)
    {
        return view('quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
        ]);

        $quiz->update($request->all());

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz updated successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | ❌ DELETE QUIZ (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('quizzes.index')
            ->with('success', 'Quiz deleted successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | ▶️ START QUIZ (STUDENT)
    |--------------------------------------------------------------------------
    */
    public function start(Quiz $quiz)
    {
        $now = Carbon::now();

        // 🚫 Block if not started
        if ($quiz->start_time && $now->lt($quiz->start_time)) {
            return redirect()->route('dashboard')
                ->with('error', 'Quiz has not started yet');
        }

        // 🚫 Block if ended
        if ($quiz->end_time && $now->gt($quiz->end_time)) {
            return redirect()->route('dashboard')
                ->with('error', 'Quiz has already ended');
        }

        $questions = Question::where('quiz_id', $quiz->id)->get();

        return view('quizzes.start', compact('quiz', 'questions'));
    }

    /*
    |--------------------------------------------------------------------------
    | 🧠 SUBMIT QUIZ
    |--------------------------------------------------------------------------
    */
    public function submit(Request $request, Quiz $quiz)
    {
        $questions = Question::where('quiz_id', $quiz->id)->get();

        $score = 0;
        $total = $questions->count();

        foreach ($questions as $question) {

            $answer = $request->input('answers.' . $question->id);

            if ($answer == $question->correct_answer) {
                $score++;
            }
        }

        $percentage = $total > 0 ? ($score / $total) * 100 : 0;

        // 🟢 Save result
        $result = Result::create([
            'user_id' => Auth::id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'total' => $total,
            'percentage' => round($percentage, 2),
        ]);

        return redirect()->route('quiz.result', $quiz->id);
    }

    /*
    |--------------------------------------------------------------------------
    | 📊 SHOW RESULT
    |--------------------------------------------------------------------------
    */
    public function results(Quiz $quiz)
    {
        $user = Auth::user();

        // 🟢 My result
        $myResult = Result::where('quiz_id', $quiz->id)
            ->where('user_id', $user->id)
            ->first();

        // 🟢 Leaderboard
        $leaderboard = Result::where('quiz_id', $quiz->id)
            ->orderByDesc('score')
            ->get();

        // 🟢 Rank calculation
        $rank = 1;
        foreach ($leaderboard as $res) {
            $res->rank = $rank++;

            // 🔥 Attach rank to my result
            if ($myResult && $res->id == $myResult->id) {
                $myResult->rank = $res->rank;
            }
        }

        return view('quizzes.results', compact('quiz', 'myResult', 'leaderboard'));
    }
}