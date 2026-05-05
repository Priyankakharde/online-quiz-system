<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Result;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /*
    |------------------------------------------------------------------
    | 📋 LIST STUDENTS (ADVANCED)
    |------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $search = $request->search;

        $students = User::where('role', 'student')
            ->withCount('results')

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
                });
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalStudents = User::where('role', 'student')->count();

        return view('students.index', compact(
            'students',
            'search',
            'totalStudents'
        ));
    }

    /*
    |------------------------------------------------------------------
    | ➕ CREATE STUDENT
    |------------------------------------------------------------------
    */
    public function create()
    {
        return view('students.create');
    }

    /*
    |------------------------------------------------------------------
    | 💾 STORE STUDENT
    |------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // auto hashed (User model)
            'role' => 'student',
        ]);

        return redirect()->route('students.index')
            ->with('success', '✅ Student created successfully!');
    }

    /*
    |------------------------------------------------------------------
    | 👁️ STUDENT DETAILS (SUPER ADVANCED)
    |------------------------------------------------------------------
    */
    public function show(User $student)
    {
        $student->load(['results.quiz']);

        // 📊 Stats
        $totalAttempts = $student->results->count();
        $avgScore = $student->results->avg('percentage') ?? 0;
        $highestScore = $student->results->max('percentage') ?? 0;

        $passed = $student->results->where('percentage', '>=', 40)->count();
        $failed = $student->results->where('percentage', '<', 40)->count();

        // 🏆 Rank Calculation
        $rankList = User::where('role', 'student')
            ->withAvg('results', 'percentage')
            ->orderByDesc('results_avg_percentage')
            ->pluck('id')
            ->toArray();

        $rank = array_search($student->id, $rankList);

        $rank = $rank !== false ? $rank + 1 : null;

        // 📅 Recent Results
        $recentResults = $student->results
            ->sortByDesc('created_at')
            ->take(5);

        return view('students.show', compact(
            'student',
            'totalAttempts',
            'avgScore',
            'highestScore',
            'passed',
            'failed',
            'rank',
            'recentResults'
        ));
    }

    /*
    |------------------------------------------------------------------
    | ✏️ EDIT STUDENT
    |------------------------------------------------------------------
    */
    public function edit(User $student)
    {
        return view('students.edit', compact('student'));
    }

    /*
    |------------------------------------------------------------------
    | 🔄 UPDATE STUDENT
    |------------------------------------------------------------------
    */
    public function update(Request $request, User $student)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // 🔐 Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $student->update($data);

        return redirect()->route('students.index')
            ->with('success', '✏️ Student updated successfully!');
    }

    /*
    |------------------------------------------------------------------
    | ❌ DELETE STUDENT (SAFE)
    |------------------------------------------------------------------
    */
    public function destroy(User $student)
    {
        // ❌ Prevent deleting yourself
        if (auth()->id() == $student->id) {
            return back()->with('error', '⚠ You cannot delete yourself');
        }

        // Delete related results
        Result::where('user_id', $student->id)->delete();

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', '🗑 Student deleted successfully!');
    }
}