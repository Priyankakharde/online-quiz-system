@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">📘 Subjects</h2>

        <a href="{{ route('subjects.create', ['quiz_id' => $quizId ?? null]) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
           + Add Subject
        </a>
    </div>

    <!-- FILTER -->
    <div class="mb-6">
        <form method="GET" action="{{ route('subjects.index') }}">
            <select name="quiz_id" onchange="this.form.submit()"
                class="border p-2 rounded w-64">

                <option value="">-- All Exams --</option>

                @foreach($quizzes as $quiz)
                    <option value="{{ $quiz->id }}"
                        {{ ($quizId == $quiz->id) ? 'selected' : '' }}>
                        {{ $quiz->title }}
                    </option>
                @endforeach

            </select>
        </form>
    </div>

    <!-- SUCCESS -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- SUBJECT CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        @forelse($subjects as $subject)

        <div class="bg-white p-5 rounded shadow hover:shadow-lg transition">

            <!-- NAME -->
            <h3 class="text-lg font-bold text-blue-600 mb-2">
                {{ $subject->name }}
            </h3>

            <!-- EXAM -->
            <p class="text-sm text-gray-600 mb-2">
                📘 Exam: {{ $subject->quiz->title ?? 'N/A' }}
            </p>

            <!-- QUESTIONS COUNT -->
            <p class="text-sm text-gray-600 mb-3">
                🧠 Questions: {{ $subject->questions->count() }}
            </p>

            <!-- ACTIONS -->
            <div class="flex flex-wrap gap-2 mt-3">

                <!-- ADD QUESTION -->
                <a href="{{ route('questions.create', ['quiz_id' => $subject->quiz_id]) }}"
                   class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded">
                   ➕ Add Question
                </a>

                <!-- VIEW QUESTIONS -->
                <a href="{{ route('questions.index', ['quiz_id' => $subject->quiz_id]) }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 text-sm rounded">
                   👁 View Questions
                </a>

                <!-- EDIT -->
                <a href="{{ route('subjects.edit', $subject->id) }}"
                   class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 text-sm rounded">
                   ✏ Edit
                </a>

                <!-- DELETE -->
                <form method="POST"
                      action="{{ route('subjects.destroy', $subject->id) }}"
                      onsubmit="return confirm('Delete this subject?')">
                    @csrf
                    @method('DELETE')

                    <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 text-sm rounded">
                        🗑 Delete
                    </button>
                </form>

            </div>

        </div>

        @empty

        <!-- EMPTY -->
        <div class="col-span-2 text-center p-10 bg-white rounded shadow text-gray-500">
            🚫 No subjects found.<br>
            <a href="{{ route('subjects.create') }}" class="text-blue-500 underline">
                Create your first subject
            </a>
        </div>

        @endforelse

    </div>

</div>

@endsection