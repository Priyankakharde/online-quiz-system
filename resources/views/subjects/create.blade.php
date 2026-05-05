@extends('layouts.app')

@section('content')

<div class="p-6 flex justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-xl">

        <!-- TITLE -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-blue-600">
                📘 Add New Subject
            </h2>

            <a href="{{ route('subjects.index') }}"
               class="text-sm text-gray-500 hover:underline">
               View All
            </a>
        </div>

        <!-- SUCCESS -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- ERRORS -->
        @if($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded mb-4">
                <ul class="text-sm">
                    @foreach($errors->all() as $error)
                        <li>⚠ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM -->
        <form action="{{ route('subjects.store') }}" method="POST" id="subjectForm">
            @csrf

            <!-- SELECT EXAM -->
            <div class="mb-5">
                <label class="block font-semibold mb-1">Select Exam</label>

                <select name="quiz_id"
                        class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400"
                        required>
                    <option value="">-- Select Exam --</option>

                    @foreach($quizzes as $quiz)
                        <option value="{{ $quiz->id }}"
                            {{ (isset($quizId) && $quizId == $quiz->id) ? 'selected' : '' }}>
                            {{ $quiz->title }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- SUBJECT NAME -->
            <div class="mb-5">
                <label class="block font-semibold mb-1">
                    Subject Name
                </label>

                <input type="text"
                       name="name"
                       id="subjectName"
                       value="{{ old('name') }}"
                       class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400"
                       placeholder="e.g. Artificial Intelligence Basics"
                       required>

                <!-- CHARACTER COUNT -->
                <div class="text-xs text-gray-500 mt-1">
                    Characters: <span id="charCount">0</span>
                </div>
            </div>

            <!-- QUICK SUGGESTIONS -->
            <div class="mb-5">
                <p class="text-sm text-gray-500 mb-2">Quick Add:</p>

                <div class="flex flex-wrap gap-2">
                    <button type="button" class="quick-btn bg-gray-200 px-3 py-1 rounded text-sm">
                        AI Basics
                    </button>
                    <button type="button" class="quick-btn bg-gray-200 px-3 py-1 rounded text-sm">
                        Machine Learning
                    </button>
                    <button type="button" class="quick-btn bg-gray-200 px-3 py-1 rounded text-sm">
                        NLP
                    </button>
                    <button type="button" class="quick-btn bg-gray-200 px-3 py-1 rounded text-sm">
                        Computer Vision
                    </button>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="flex justify-between items-center mt-6">

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow">
                    💾 Save Subject
                </button>

                <a href="{{ route('subjects.index', ['quiz_id' => $quizId ?? null]) }}"
                   class="text-gray-600 hover:underline">
                    ← Back
                </a>

            </div>

        </form>

    </div>

</div>

<!-- 🔥 JAVASCRIPT (ADVANCED UX) -->
<script>
    const input = document.getElementById('subjectName');
    const counter = document.getElementById('charCount');
    const quickBtns = document.querySelectorAll('.quick-btn');

    // Character counter
    input.addEventListener('input', () => {
        counter.innerText = input.value.length;
    });

    // Quick add buttons
    quickBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            input.value = btn.innerText;
            counter.innerText = input.value.length;
            input.focus();
        });
    });

    // Auto focus
    window.onload = () => {
        input.focus();
    };
</script>

@endsection