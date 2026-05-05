<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Subject;
use App\Models\Quiz;

class QuestionController extends Controller
{
    /*
    |--------------------------------------------------
    | 📋 Show All Questions (with relations)
    |--------------------------------------------------
    */
    public function index()
    {
        $questions = Question::with(['subject.quiz'])->latest()->get();

        return view('questions.index', compact('questions'));
    }

    /*
    |--------------------------------------------------
    | ➕ Create Question Form (FIXED 🔥)
    |--------------------------------------------------
    */
    public function create()
    {
        $subjects = Subject::with('quiz')->get(); // subject + quiz relation
        $quizzes = Quiz::all(); // 🔥 REQUIRED for dropdown

        return view('questions.create', compact('subjects', 'quizzes'));
    }

    /*
    |--------------------------------------------------
    | 💾 Store Question
    |--------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'question' => 'required|string|min:5',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'nullable|string',
            'option_d' => 'nullable|string',
            'correct_answer' => 'required|in:a,b,c,d',
        ]);

        Question::create($request->all());

        return redirect()->route('questions.index')
            ->with('success', '✅ Question added successfully');
    }

    /*
    |--------------------------------------------------
    | ✏️ Edit Question
    |--------------------------------------------------
    */
    public function edit($id)
    {
        $question = Question::findOrFail($id);

        $subjects = Subject::with('quiz')->get();
        $quizzes = Quiz::all(); // for dropdown

        return view('questions.edit', compact('question', 'subjects', 'quizzes'));
    }

    /*
    |--------------------------------------------------
    | 🔄 Update Question
    |--------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'question' => 'required|string|min:5',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'nullable|string',
            'option_d' => 'nullable|string',
            'correct_answer' => 'required|in:a,b,c,d',
        ]);

        $question->update($request->all());

        return redirect()->route('questions.index')
            ->with('success', '✏️ Question updated');
    }

    /*
    |--------------------------------------------------
    | ❌ Delete Question
    |--------------------------------------------------
    */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return redirect()->route('questions.index')
            ->with('success', '🗑 Question deleted');
    }
}