<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $quizzes = Quiz::latest()->take(5)->get();

        $totalExams = Quiz::count();
        $completed = Result::where('user_id', $userId)->count();
        $ongoing = Quiz::where('status', 'Ongoing')->count();
        $upcoming = Quiz::where('status', 'Upcoming')->count();

        $upcomingExams = Quiz::where('status', 'Upcoming')->get();

        return view('dashboard', compact(
            'quizzes',
            'totalExams',
            'completed',
            'ongoing',
            'upcoming',
            'upcomingExams'
        ));
    }
}