@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white p-6 rounded shadow mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold">
                {{ $quiz->title }}
            </h2>
            <p class="text-sm opacity-90">
                {{ $quiz->description ?? 'No description available' }}
            </p>
        </div>

        <div class="text-right">
            <p class="text-sm opacity-80">Duration</p>
            <p class="font-bold text-lg">{{ $quiz->duration }} mins</p>
        </div>
    </div>

    <!-- INSTRUCTIONS -->
    <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-6 text-sm">
        ⚠ Select a subject to start your quiz. Once started, timer will begin.
    </div>

    <!-- SUBJECT LIST -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        @forelse($quiz->subjects as $subject)

            @php
                $questionCount = $subject->questions->count();
            @endphp

            <div class="bg-white p-5 rounded shadow hover:shadow-lg transition border">

                <!-- SUBJECT NAME -->
                <h3 class="text-lg font-semibold text-gray-800 mb-2">
                    📘 {{ $subject->name }}
                </h3>

                <!-- DESCRIPTION (optional) -->
                @if($subject->description)
                    <p class="text-xs text-gray-500 mb-2">
                        {{ $subject->description }}
                    </p>
                @endif

                <!-- DETAILS -->
                <p class="text-sm text-gray-500 mb-3">
                    Questions:
                    <span class="font-semibold text-blue-600">
                        {{ $questionCount }}
                    </span>
                </p>

                <!-- START BUTTON -->
                @if($questionCount > 0)
                    <a href="{{ route('quiz.start.subject', [$quiz->id, $subject->id]) }}"
                       class="block text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-semibold">
                        ▶ Start Quiz
                    </a>
                @else
                    <button class="block w-full text-center bg-gray-400 text-white px-4 py-2 rounded text-sm cursor-not-allowed">
                        ❌ No Questions
                    </button>
                @endif

            </div>

        @empty

            <div class="col-span-2 text-center text-gray-500 bg-white p-6 rounded shadow">
                ❌ No subjects available for this quiz.
            </div>

        @endforelse

    </div>

    <!-- FOOTER ACTIONS -->
    <div class="mt-8 flex justify-between items-center">

        <a href="{{ route('dashboard') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
            ⬅ Back to Dashboard
        </a>

        <span class="text-sm text-gray-500">
            Total Subjects: {{ $quiz->subjects->count() }}
        </span>

    </div>

</div>

@endsection