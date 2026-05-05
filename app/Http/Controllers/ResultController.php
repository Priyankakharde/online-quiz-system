<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\User;
use App\Models\Quiz;
use Barryvdh\DomPDF\Facade\Pdf;

class ResultController extends Controller
{
    /*
    |------------------------------------------------------------------
    | 📋 ALL RESULTS (ADMIN PANEL)
    |------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $quizId = $request->quiz_id;
        $search = $request->search;

        $results = Result::with(['user', 'quiz'])

            ->when($quizId, function ($query) use ($quizId) {
                $query->where('quiz_id', $quizId);
            })

            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        $quizzes = Quiz::all();

        return view('results.index', compact('results', 'quizzes', 'quizId', 'search'));
    }

    /*
    |------------------------------------------------------------------
    | 👁️ SINGLE RESULT VIEW
    |------------------------------------------------------------------
    */
    public function show(Result $result)
    {
        $result->load(['user', 'quiz']);
        return view('results.show', compact('result'));
    }

    /*
    |------------------------------------------------------------------
    | ❌ DELETE RESULT
    |------------------------------------------------------------------
    */
    public function destroy(Result $result)
    {
        $result->delete();
        return back()->with('success', '🗑 Result deleted successfully');
    }

    /*
    |------------------------------------------------------------------
    | 🏆 LEADERBOARD (ADVANCED 🔥)
    |------------------------------------------------------------------
    */
    public function leaderboard(Request $request)
    {
        $quizId = $request->quiz_id;

        $results = Result::with(['user', 'quiz'])
            ->when($quizId, function ($query) use ($quizId) {
                $query->where('quiz_id', $quizId);
            })
            ->orderByDesc('percentage')
            ->orderByDesc('score')
            ->get();

        // 🔥 Rank with tie handling
        $rank = 1;
        $previous = null;

        foreach ($results as $result) {

            if ($previous &&
                $previous->percentage == $result->percentage &&
                $previous->score == $result->score) {

                $result->rank = $previous->rank;

            } else {
                $result->rank = $rank;
            }

            $previous = $result;
            $rank++;
        }

        $quizzes = Quiz::all();

        // 📊 Advanced stats
        $topper = $results->first();
        $average = round($results->avg('percentage') ?? 0, 2);
        $totalStudents = $results->count();

        // 🔥 Top 3 (for medals UI later)
        $topThree = $results->take(3);

        return view('results.leaderboard', compact(
            'results',
            'quizzes',
            'quizId',
            'topper',
            'average',
            'totalStudents',
            'topThree'
        ));
    }

    /*
    |------------------------------------------------------------------
    | 📊 QUIZ RESULT PAGE (STUDENT)
    |------------------------------------------------------------------
    */
    public function quizResults(Quiz $quiz)
    {
        $results = Result::where('quiz_id', $quiz->id)
            ->with('user')
            ->orderByDesc('percentage')
            ->get();

        $topper = $results->first();
        $average = round($results->avg('percentage') ?? 0, 2);

        $myResult = $results->where('user_id', auth()->id())->first();

        return view('quizzes.results', [
            'quiz' => $quiz,
            'results' => $results,
            'topper' => $topper,
            'average' => $average,
            'myResult' => $myResult,
        ]);
    }

    /*
    |------------------------------------------------------------------
    | 🎓 DOWNLOAD CERTIFICATE (FINAL FIXED 🔥)
    |------------------------------------------------------------------
    */
    public function downloadCertificate($id)
    {
        $result = Result::with(['user', 'quiz'])->findOrFail($id);

        // ✅ Safe fallback (no crash)
        $user = $result->user ?? null;
        $quiz = $result->quiz ?? null;

        if (!$user || !$quiz) {
            return back()->with('error', '❌ Certificate data missing');
        }

        // 🔥 Optimized PDF
        $pdf = Pdf::loadView('results.certificate', [
            'result' => $result,
            'user'   => $user,
            'quiz'   => $quiz
        ])->setPaper('A4', 'landscape');

        return $pdf->download('certificate-' . $user->name . '.pdf');
    }
}