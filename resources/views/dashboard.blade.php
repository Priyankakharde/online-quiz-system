@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Online Quiz Dashboard</h2>
            <p class="text-sm text-gray-500">Welcome back, {{ auth()->user()->name ?? 'User' }}</p>
        </div>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-blue-500 text-white p-6 rounded-xl shadow hover:scale-105 transition">
            <p class="text-sm opacity-80">Total Exams</p>
            <h2 class="text-3xl font-bold">{{ $totalExams ?? 0 }}</h2>
        </div>

        <div class="bg-green-500 text-white p-6 rounded-xl shadow hover:scale-105 transition">
            <p class="text-sm opacity-80">Completed</p>
            <h2 class="text-3xl font-bold">{{ $completed ?? 0 }}</h2>
        </div>

        <div class="bg-orange-500 text-white p-6 rounded-xl shadow hover:scale-105 transition">
            <p class="text-sm opacity-80">On-going</p>
            <h2 class="text-3xl font-bold">{{ $ongoing ?? 0 }}</h2>
        </div>

        <div class="bg-red-500 text-white p-6 rounded-xl shadow hover:scale-105 transition">
            <p class="text-sm opacity-80">Upcoming</p>
            <h2 class="text-3xl font-bold">{{ $upcoming ?? 0 }}</h2>
        </div>

    </div>

    <!-- QUICK ACTIONS -->
    <div class="bg-white p-5 rounded-xl shadow mb-8">
        <h3 class="font-semibold mb-4 text-gray-700">Quick Actions</h3>

        <div class="flex flex-wrap gap-3">

            <a href="{{ route('quizzes.index') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                Manage Exams
            </a>

            <a href="{{ route('subjects.index') }}"
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                Subjects
            </a>

            <a href="{{ route('questions.index') }}"
               class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-sm">
                Questions
            </a>

            <a href="{{ route('results.index') }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                Results
            </a>

        </div>
    </div>

    <!-- RECENT EXAMS -->
    <div class="bg-white p-6 rounded-xl shadow mb-8">
        <h3 class="font-semibold mb-4 text-gray-700">Recent Exams</h3>

        @forelse($quizzes ?? [] as $quiz)

            @php
                $statusClass = match($quiz->status ?? 'Upcoming') {
                    'Upcoming' => 'bg-red-100 text-red-600',
                    'Ongoing' => 'bg-orange-100 text-orange-600',
                    'Completed' => 'bg-green-100 text-green-600',
                    default => 'bg-gray-100 text-gray-600',
                };
            @endphp

            <div class="flex flex-col md:flex-row md:justify-between md:items-center border-b py-4 gap-3 hover:bg-gray-50 px-3 rounded">

                <!-- LEFT -->
                <div>
                    <h4 class="font-semibold text-gray-800">{{ $quiz->title }}</h4>

                    <span class="text-xs px-2 py-1 rounded-full mt-1 inline-block {{ $statusClass }}">
                        {{ $quiz->status ?? 'Upcoming' }}
                    </span>
                </div>

                <!-- RIGHT -->
                <div>

                    @if($quiz->attempted ?? false)
                        <a href="{{ route('quiz.result', $quiz->id) }}"
                           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
                            View Result
                        </a>

                    @elseif(($quiz->status ?? '') === 'Ongoing')
                        <a href="{{ route('quiz.start', $quiz->id) }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                            Start Quiz
                        </a>

                    @else
                        <button class="bg-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm cursor-not-allowed">
                            Locked
                        </button>
                    @endif

                </div>

            </div>

        @empty
            <div class="text-center py-6 text-gray-500">
                No exams found.
            </div>
        @endforelse

    </div>

    <!-- UPCOMING -->
    <div class="bg-white p-6 rounded-xl shadow">

        <h3 class="font-semibold mb-4 text-gray-700">Upcoming Exams</h3>

        @forelse($upcomingExams ?? [] as $quiz)
            <div class="border-b py-2 text-gray-700">
                {{ $quiz->title }}
            </div>
        @empty
            <p class="text-gray-500">No upcoming exams.</p>
        @endforelse

    </div>

</div>

@endsection