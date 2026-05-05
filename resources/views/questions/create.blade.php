@extends('layouts.app')

@section('content')

<div class="p-6 flex justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-3xl">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-blue-600">
                ➕ Add New Question
            </h2>

            <a href="{{ route('questions.index') }}"
               class="text-gray-500 hover:underline text-sm">
               ← Back
            </a>
        </div>

        <!-- ERROR -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 p-3 mb-4 rounded">
                <ul class="text-sm">
                    @foreach ($errors->all() as $error)
                        <li>⚠ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM -->
        <form action="{{ route('questions.store') }}" method="POST">
            @csrf

            <!-- QUIZ (SAFE FIX) -->
            <div class="mb-5">
                <label class="font-semibold">Select Exam</label>
                <select id="quizDropdown" name="quiz_id"
                        class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400">
                    <option value="">-- Select Exam --</option>

                    @if(isset($quizzes))
                        @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}">
                                {{ $quiz->title }}
                            </option>
                        @endforeach
                    @endif

                </select>
            </div>

            <!-- SUBJECT -->
            <div class="mb-5">
                <label class="font-semibold">Select Subject</label>
                <select id="subjectDropdown" name="subject_id"
                        class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-400"
                        required>

                    <option value="">-- Select Subject --</option>

                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}"
                                data-quiz="{{ $subject->quiz_id }}">
                            {{ $subject->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- QUESTION -->
            <div class="mb-5">
                <label class="font-semibold">Question</label>
                <textarea id="questionInput"
                          name="question"
                          class="w-full border p-3 rounded focus:ring-2 focus:ring-blue-400"
                          rows="3"
                          required></textarea>

                <p class="text-xs text-gray-500 mt-1">
                    Characters: <span id="charCount">0</span>
                </p>
            </div>

            <!-- OPTIONS -->
            <div class="grid grid-cols-2 gap-4 mb-5">

                <div>
                    <label>Option A</label>
                    <input type="text" id="optA" name="option_a"
                           class="w-full border p-2 rounded" required>
                </div>

                <div>
                    <label>Option B</label>
                    <input type="text" id="optB" name="option_b"
                           class="w-full border p-2 rounded" required>
                </div>

                <div>
                    <label>Option C</label>
                    <input type="text" id="optC" name="option_c"
                           class="w-full border p-2 rounded">
                </div>

                <div>
                    <label>Option D</label>
                    <input type="text" id="optD" name="option_d"
                           class="w-full border p-2 rounded">
                </div>

            </div>

            <!-- CORRECT ANSWER -->
            <div class="mb-6">
                <label class="font-semibold">Correct Answer</label>
                <select name="correct_answer"
                        class="w-full border p-2 rounded"
                        required>

                    <option value="">-- Select Correct Answer --</option>
                    <option value="a">Option A</option>
                    <option value="b">Option B</option>
                    <option value="c">Option C</option>
                    <option value="d">Option D</option>

                </select>
            </div>

            <!-- PREVIEW -->
            <div class="bg-gray-50 p-4 rounded mb-6 border">
                <h3 class="font-semibold mb-2">👀 Live Preview</h3>
                <p id="previewQuestion" class="mb-2"></p>
                <ul class="text-sm">
                    <li>A: <span id="pA"></span></li>
                    <li>B: <span id="pB"></span></li>
                    <li>C: <span id="pC"></span></li>
                    <li>D: <span id="pD"></span></li>
                </ul>
            </div>

            <!-- BUTTON -->
            <div class="text-center">
                <button class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded shadow">
                    💾 Save Question
                </button>
            </div>

        </form>

    </div>

</div>

<!-- 🔥 JS -->
<script>
    const quizDropdown = document.getElementById('quizDropdown');
    const subjectDropdown = document.getElementById('subjectDropdown');

    quizDropdown?.addEventListener('change', function () {
        const selectedQuiz = this.value;

        Array.from(subjectDropdown.options).forEach(option => {
            if (!option.value) return;

            option.hidden = option.dataset.quiz != selectedQuiz;
        });

        subjectDropdown.value = "";
    });

    const questionInput = document.getElementById('questionInput');
    const charCount = document.getElementById('charCount');
    const previewQuestion = document.getElementById('previewQuestion');

    questionInput.addEventListener('input', () => {
        charCount.innerText = questionInput.value.length;
        previewQuestion.innerText = questionInput.value;
    });

    const inputs = ['optA','optB','optC','optD'];
    inputs.forEach((id, index) => {
        document.getElementById(id).addEventListener('input', function () {
            document.getElementById('p' + ['A','B','C','D'][index]).innerText = this.value;
        });
    });
</script>

@endsection