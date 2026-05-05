@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold flex items-center gap-2">
            🧠 Questions
            <span class="bg-gray-200 text-sm px-2 py-1 rounded">
                {{ $questions->count() }}
            </span>
        </h2>

        <a href="{{ route('questions.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
           + Add Question
        </a>
    </div>

    <!-- SEARCH -->
    <div class="mb-4">
        <input type="text"
               id="search"
               placeholder="Search question..."
               class="w-full border p-2 rounded shadow-sm"
               onkeyup="filterQuestions()">
    </div>

    <!-- SUCCESS -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- QUESTIONS -->
    <div id="questionList" class="space-y-5">

        @forelse($questions as $question)

        <div class="question-item bg-white p-5 rounded shadow hover:shadow-lg transition border">

            <!-- TOP -->
            <div class="flex justify-between items-start mb-2">

                <h3 class="font-semibold text-lg">
                    {{ $loop->iteration }}. {{ $question->question }}
                </h3>

                <span class="text-xs bg-gray-200 px-2 py-1 rounded">
                    ID: {{ $question->id }}
                </span>

            </div>

            <!-- SUBJECT + EXAM -->
            <p class="text-sm text-gray-600 mb-3">
                📘 <strong>Subject:</strong> {{ $question->subject->name ?? 'N/A' }}
                &nbsp; | &nbsp;
                📝 <strong>Exam:</strong> {{ $question->subject->quiz->title ?? 'N/A' }}
            </p>

            <!-- OPTIONS -->
            <div class="grid grid-cols-2 gap-3 text-sm mb-3">

                <div class="p-2 rounded border {{ $question->correct_answer == 'a' ? 'bg-green-100 border-green-400' : '' }}">
                    A: {{ $question->option_a }}
                </div>

                <div class="p-2 rounded border {{ $question->correct_answer == 'b' ? 'bg-green-100 border-green-400' : '' }}">
                    B: {{ $question->option_b }}
                </div>

                <div class="p-2 rounded border {{ $question->correct_answer == 'c' ? 'bg-green-100 border-green-400' : '' }}">
                    C: {{ $question->option_c }}
                </div>

                <div class="p-2 rounded border {{ $question->correct_answer == 'd' ? 'bg-green-100 border-green-400' : '' }}">
                    D: {{ $question->option_d }}
                </div>

            </div>

            <!-- CORRECT -->
            <p class="text-green-600 font-semibold mb-3">
                ✔ Correct Answer: {{ strtoupper($question->correct_answer) }}
            </p>

            <!-- ACTIONS -->
            <div class="flex gap-2">

                <a href="{{ route('questions.edit', $question->id) }}"
                   class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 text-sm rounded">
                   ✏ Edit
                </a>

                <form method="POST"
                      action="{{ route('questions.destroy', $question->id) }}"
                      onsubmit="return confirm('Delete this question?')">
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
        <div class="text-center p-10 bg-white rounded shadow text-gray-500">
            🚫 No questions found.<br><br>

            <a href="{{ route('questions.create') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded shadow">
               + Add your first question
            </a>
        </div>

        @endforelse

    </div>

</div>

<!-- 🔍 SEARCH SCRIPT -->
<script>
function filterQuestions() {
    let input = document.getElementById('search').value.toLowerCase();
    let items = document.querySelectorAll('.question-item');

    items.forEach(item => {
        let text = item.innerText.toLowerCase();
        item.style.display = text.includes(input) ? '' : 'none';
    });
}
</script>

@endsection