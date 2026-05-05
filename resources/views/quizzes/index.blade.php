@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">📚 Manage Exams</h2>

        <a href="{{ route('quizzes.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
           + Add Exam
        </a>
    </div>

    <!-- SUCCESS -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- FILTER + SEARCH -->
    <div class="flex flex-wrap gap-3 mb-6">

        <!-- SEARCH -->
        <form method="GET" class="flex gap-2 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search exam..."
                class="border p-2 rounded w-full md:w-64">

            <button class="bg-gray-700 text-white px-4 rounded">
                Search
            </button>
        </form>

        <!-- FILTER -->
        <div class="flex gap-2">
            <a href="{{ route('quizzes.index') }}"
               class="px-3 py-1 rounded {{ request('status') == '' ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
               All
            </a>

            <a href="?status=ongoing"
               class="px-3 py-1 rounded {{ request('status') == 'ongoing' ? 'bg-green-500 text-white' : 'bg-gray-200' }}">
               Ongoing
            </a>

            <a href="?status=upcoming"
               class="px-3 py-1 rounded {{ request('status') == 'upcoming' ? 'bg-yellow-500 text-white' : 'bg-gray-200' }}">
               Upcoming
            </a>

            <a href="?status=completed"
               class="px-3 py-1 rounded {{ request('status') == 'completed' ? 'bg-gray-500 text-white' : 'bg-gray-200' }}">
               Completed
            </a>
        </div>

    </div>

    <!-- QUIZ LIST -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        @forelse($quizzes as $quiz)

        @php
            $start = $quiz->start_time ? \Carbon\Carbon::parse($quiz->start_time) : null;
            $end = $start ? $start->copy()->addMinutes($quiz->duration) : null;

            if (!$quiz->start_time) {
                $status = 'Draft';
                $color = 'bg-gray-500';
            } elseif(now()->lt($start)) {
                $status = 'Upcoming';
                $color = 'bg-yellow-500';
            } elseif(now()->between($start, $end)) {
                $status = 'Ongoing';
                $color = 'bg-green-500';
            } else {
                $status = 'Completed';
                $color = 'bg-blue-500';
            }

            $questionCount = $quiz->subjects
                ->flatMap(fn($s) => $s->questions)
                ->count();

            $attempts = $quiz->results->count() ?? 0;
        @endphp

        <div class="bg-white p-5 rounded shadow hover:shadow-lg transition">

            <!-- TITLE -->
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-bold text-blue-600">
                    {{ $quiz->title }}
                </h3>

                <span class="text-xs text-white px-2 py-1 rounded {{ $color }}">
                    {{ $status }}
                </span>
            </div>

            <!-- DESCRIPTION -->
            <p class="text-sm text-gray-500 mb-2">
                {{ $quiz->description ?? 'No description available' }}
            </p>

            <!-- DETAILS -->
            <p class="text-sm text-gray-600">📂 {{ $quiz->category->name ?? 'N/A' }}</p>

            <p class="text-sm text-gray-600">
                📅 {{ $start ? $start->format('d M Y h:i A') : 'Not set' }}
            </p>

            <p class="text-sm text-gray-600 mb-2">
                ⏱ {{ $quiz->duration }} mins
            </p>

            <!-- STATS -->
            <div class="flex gap-4 text-xs text-gray-500 mb-3">
                <span>🧠 {{ $questionCount }} Questions</span>
                <span>👥 {{ $attempts }} Attempts</span>
            </div>

            <!-- ACTIONS -->
            <div class="flex flex-wrap gap-2 mt-3">

                @if(auth()->user()->isAdmin())
                <a href="{{ route('quizzes.edit', $quiz->id) }}"
                   class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 text-sm rounded">
                   ✏ Edit
                </a>
                @endif

                <!-- ✅ START QUIZ (IMPROVED LOGIC) -->
                @if(auth()->user()->isStudent())
                    @if($status == 'Ongoing')
                        <a href="{{ route('quiz.start', $quiz->id) }}"
                           class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded">
                           ▶ Start Quiz
                        </a>
                    @elseif($status == 'Upcoming')
                        <span class="bg-yellow-400 text-white px-3 py-1 text-sm rounded">
                            ⏳ Not Started
                        </span>
                    @else
                        <span class="bg-gray-400 text-white px-3 py-1 text-sm rounded">
                            🚫 Closed
                        </span>
                    @endif
                @endif

                @if(auth()->user()->isAdmin())
                <a href="{{ route('questions.index', ['quiz_id' => $quiz->id]) }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded">
                   🧠 Questions
                </a>
                @endif

                <a href="{{ route('quizzes.results', $quiz->id) }}"
                   class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 text-sm rounded">
                   📊 Results
                </a>

                @if(auth()->user()->isAdmin())
                <form method="POST"
                      action="{{ route('quizzes.destroy', $quiz->id) }}"
                      onsubmit="return confirm('⚠ Are you sure?')">
                    @csrf
                    @method('DELETE')

                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded">
                        🗑 Delete
                    </button>
                </form>
                @endif

            </div>

        </div>

        @empty

        <div class="col-span-2 text-center p-10 bg-white rounded shadow text-gray-500">
            🚫 No exams available<br>

            <a href="{{ route('quizzes.create') }}"
               class="text-blue-500 underline">
                Create your first exam
            </a>
        </div>

        @endforelse

    </div>

</div>

@endsection