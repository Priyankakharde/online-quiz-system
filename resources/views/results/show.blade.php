@extends('layouts.app')

@section('content')

<div class="p-6 flex justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-3xl">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-indigo-600">
                📊 Result Details
            </h2>

            <span class="px-3 py-1 text-white rounded text-sm
                {{ $result->status == 'Passed' ? 'bg-green-500' : 'bg-red-500' }}">
                {{ $result->status }}
            </span>
        </div>

        <!-- STUDENT + EXAM INFO -->
        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">

            <div>
                <p><strong>👤 Student:</strong> {{ $result->user->name }}</p>
                <p><strong>📧 Email:</strong> {{ $result->user->email }}</p>
            </div>

            <div>
                <p><strong>📘 Exam:</strong> {{ $result->quiz->title }}</p>
                <p><strong>📅 Attempted:</strong> {{ $result->attempted_at }}</p>
            </div>

        </div>

        <!-- SCORE CARDS -->
        <div class="grid grid-cols-3 gap-4 mb-6 text-center">

            <div class="bg-blue-100 p-4 rounded">
                <p class="text-sm text-gray-600">Score</p>
                <p class="text-xl font-bold text-blue-600">
                    {{ $result->score }}/{{ $result->total }}
                </p>
            </div>

            <div class="bg-purple-100 p-4 rounded">
                <p class="text-sm text-gray-600">Percentage</p>
                <p class="text-xl font-bold text-purple-600">
                    {{ $result->percentage }}%
                </p>
            </div>

            <div class="bg-yellow-100 p-4 rounded">
                <p class="text-sm text-gray-600">Rank</p>
                <p class="text-xl font-bold text-yellow-600">
                    #{{ $result->rank }}
                </p>
            </div>

        </div>

        <!-- GRADE -->
        <div class="text-center mb-6">
            <p class="text-sm text-gray-600">Grade</p>
            <p class="text-2xl font-bold text-indigo-600">
                {{ $result->grade }}
            </p>
        </div>

        <!-- PROGRESS BAR -->
        <div class="mb-6">
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div class="h-4 rounded-full
                    {{ $result->status == 'Passed' ? 'bg-green-500' : 'bg-red-500' }}"
                    style="width: {{ $result->percentage }}%">
                </div>
            </div>
        </div>

        <!-- PERFORMANCE MESSAGE -->
        <div class="text-center mb-6">
            @if($result->percentage >= 80)
                <p class="text-green-600 font-semibold">🔥 Excellent Performance!</p>
            @elseif($result->percentage >= 50)
                <p class="text-blue-600 font-semibold">👍 Good Job!</p>
            @else
                <p class="text-red-600 font-semibold">⚠ Needs Improvement</p>
            @endif
        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex justify-between flex-wrap gap-3">

            <a href="{{ route('results.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                ← Back
            </a>

            <a href="{{ route('leaderboard') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                🏆 Leaderboard
            </a>

            <a href="{{ route('certificate.download', $result->id) }}"
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                🎓 Download Certificate
            </a>

        </div>

    </div>

</div>

@endsection