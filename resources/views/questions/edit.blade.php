@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">✏ Edit Question</h2>

        <a href="{{ route('questions.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">
            ← Back
        </a>
    </div>

    <!-- ERROR -->
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM -->
    <div class="bg-white p-6 rounded shadow">

        <form method="POST" action="{{ route('questions.update', $question->id) }}">
            @csrf
            @method('PUT')

            <!-- SUBJECT -->
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Select Subject</label>

                <select name="subject_id" class="border p-2 rounded w-full" required>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}"
                            {{ $question->subject_id == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }} ({{ $subject->quiz->title ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- QUESTION -->
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Question</label>
                <textarea name="question"
                    class="w-full border p-2 rounded"
                    rows="3"
                    required>{{ $question->question }}</textarea>
            </div>

            <!-- OPTIONS -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block mb-1">Option A</label>
                    <input type="text" name="option_a"
                        value="{{ $question->option_a }}"
                        class="border p-2 rounded w-full" required>
                </div>

                <div>
                    <label class="block mb-1">Option B</label>
                    <input type="text" name="option_b"
                        value="{{ $question->option_b }}"
                        class="border p-2 rounded w-full" required>
                </div>

                <div>
                    <label class="block mb-1">Option C</label>
                    <input type="text" name="option_c"
                        value="{{ $question->option_c }}"
                        class="border p-2 rounded w-full" required>
                </div>

                <div>
                    <label class="block mb-1">Option D</label>
                    <input type="text" name="option_d"
                        value="{{ $question->option_d }}"
                        class="border p-2 rounded w-full" required>
                </div>
            </div>

            <!-- CORRECT ANSWER -->
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Correct Answer</label>

                <select name="correct_answer"
                        class="border p-2 rounded w-full"
                        required>

                    <option value="a" {{ $question->correct_answer == 'a' ? 'selected' : '' }}>A</option>
                    <option value="b" {{ $question->correct_answer == 'b' ? 'selected' : '' }}>B</option>
                    <option value="c" {{ $question->correct_answer == 'c' ? 'selected' : '' }}>C</option>
                    <option value="d" {{ $question->correct_answer == 'd' ? 'selected' : '' }}>D</option>

                </select>
            </div>

            <!-- BUTTON -->
            <button class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded shadow">
                ✅ Update Question
            </button>

        </form>

    </div>

</div>

@endsection