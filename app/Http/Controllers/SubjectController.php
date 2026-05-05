<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Quiz;

class SubjectController extends Controller
{
    /*
    |------------------------------------------------------------------
    | 📋 List Subjects (Filter by Quiz + Pagination)
    |------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $quizId = $request->quiz_id;

        $subjects = Subject::with(['quiz', 'questions'])
            ->when($quizId, function ($query) use ($quizId) {
                $query->where('quiz_id', $quizId);
            })
            ->latest()
            ->paginate(10); // ✅ pagination added

        $quizzes = Quiz::all();

        return view('subjects.index', compact('subjects', 'quizzes', 'quizId'));
    }

    /*
    |------------------------------------------------------------------
    | ➕ Create Subject
    |------------------------------------------------------------------
    */
    public function create(Request $request)
    {
        $quizId = $request->quiz_id;
        $quizzes = Quiz::all();

        return view('subjects.create', compact('quizzes', 'quizId'));
    }

    /*
    |------------------------------------------------------------------
    | 💾 Store Subject (Duplicate Protection)
    |------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'name' => 'required|string|max:255',
        ]);

        // 🚫 Prevent duplicate subject in same quiz
        $exists = Subject::where('quiz_id', $request->quiz_id)
            ->where('name', $request->name)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'name' => '⚠ Subject already exists for this exam!'
            ])->withInput();
        }

        Subject::create([
            'quiz_id' => $request->quiz_id,
            'name' => $request->name,
        ]);

        return redirect()->route('subjects.index', [
            'quiz_id' => $request->quiz_id
        ])->with('success', '✅ Subject created successfully!');
    }

    /*
    |------------------------------------------------------------------
    | 👁️ Show Subject (With Questions)
    |------------------------------------------------------------------
    */
    public function show(Subject $subject)
    {
        $subject->load('questions');

        return view('subjects.show', compact('subject'));
    }

    /*
    |------------------------------------------------------------------
    | ✏️ Edit Subject
    |------------------------------------------------------------------
    */
    public function edit(Subject $subject)
    {
        $quizzes = Quiz::all();

        return view('subjects.edit', compact('subject', 'quizzes'));
    }

    /*
    |------------------------------------------------------------------
    | 🔄 Update Subject (Duplicate Check)
    |------------------------------------------------------------------
    */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'name' => 'required|string|max:255',
        ]);

        // 🚫 Prevent duplicate (except current)
        $exists = Subject::where('quiz_id', $request->quiz_id)
            ->where('name', $request->name)
            ->where('id', '!=', $subject->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'name' => '⚠ Subject already exists for this exam!'
            ])->withInput();
        }

        $subject->update([
            'quiz_id' => $request->quiz_id,
            'name' => $request->name,
        ]);

        return redirect()->route('subjects.index', [
            'quiz_id' => $request->quiz_id
        ])->with('success', '✏️ Subject updated successfully!');
    }

    /*
    |------------------------------------------------------------------
    | ❌ Delete Subject (Safe Delete)
    |------------------------------------------------------------------
    */
    public function destroy(Subject $subject)
    {
        // 🚫 Prevent delete if questions exist
        if ($subject->questions()->count() > 0) {
            return back()->withErrors([
                'error' => '❌ Cannot delete subject with questions!'
            ]);
        }

        $subject->delete();

        return back()->with('success', '🗑 Subject deleted successfully!');
    }
}