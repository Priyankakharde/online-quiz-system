@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="sticky top-0 z-50 bg-white p-4 rounded shadow mb-4 flex justify-between items-center border">
        
        <h2 class="font-bold text-blue-600 text-lg">
            {{ $quiz->title }}
        </h2>

        <div id="timer" 
             class="bg-red-500 text-white px-5 py-2 rounded font-bold text-lg shadow">
            00:00
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4">

        <!-- QUESTIONS -->
        <div class="col-span-3">

            <form method="POST" action="{{ route('quiz.submit', $quiz->id) }}" id="quizForm">
                @csrf

                @php $qNo = 0; @endphp

                @foreach($quiz->subjects as $subject)
                    @foreach($subject->questions as $question)

                    <div class="bg-white p-6 rounded shadow mb-4 question-block hidden border"
                         id="question-{{ $question->id }}">

                        <h3 class="font-semibold mb-5 text-lg">
                            Q{{ ++$qNo }}. {{ $question->question }}
                        </h3>

                        @foreach(['a','b','c','d'] as $opt)
                            <label class="block border p-3 rounded cursor-pointer hover:bg-gray-100 mb-3 transition">
                                <input type="radio"
                                       name="answers[{{ $question->id }}]"
                                       value="{{ $opt }}"
                                       class="mr-2 answer-option"
                                       data-id="{{ $question->id }}">
                                <span class="font-medium">
                                    {{ strtoupper($opt) }}.
                                </span>
                                {{ $question['option_'.$opt] }}
                            </label>
                        @endforeach

                        <!-- ACTIONS -->
                        <div class="flex justify-between mt-6">

                            <button type="button" onclick="prevQ()"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1 rounded">
                                ⬅ Prev
                            </button>

                            <button type="button" onclick="markReview({{ $question->id }})"
                                class="bg-yellow-400 hover:bg-yellow-500 px-4 py-1 rounded">
                                ⭐ Mark
                            </button>

                            <button type="button" onclick="nextQ()"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded">
                                Next ➡
                            </button>

                        </div>

                    </div>

                    @endforeach
                @endforeach

                <!-- SUBMIT -->
                <div class="text-center mt-8">
                    <button type="submit"
                        onclick="return confirm('Submit your quiz?')"
                        class="bg-green-600 hover:bg-green-700 text-white px-10 py-2 rounded shadow text-lg">
                        Submit Quiz
                    </button>
                </div>

            </form>

        </div>

        <!-- SIDE PANEL -->
        <div class="col-span-1 bg-white p-4 rounded shadow sticky top-20 h-fit border">

            <h3 class="font-semibold mb-3 text-center">Questions</h3>

            <div class="grid grid-cols-5 gap-2">

                @php $index = 0; @endphp

                @foreach($quiz->subjects as $subject)
                    @foreach($subject->questions as $question)

                    <button onclick="goToQ({{ $index }})"
                        id="nav-{{ $question->id }}"
                        class="p-2 text-sm rounded bg-gray-200 hover:bg-gray-300 transition">
                        {{ ++$index }}
                    </button>

                    @endforeach
                @endforeach

            </div>

            <!-- PROGRESS -->
            <div class="mt-5">
                <p class="text-sm mb-1">
                    Progress: <span id="progressText">0%</span>
                </p>

                <div class="bg-gray-200 h-2 rounded">
                    <div id="progressBar" class="bg-blue-500 h-2 rounded" style="width:0%"></div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- JS -->
<script>
let questions = document.querySelectorAll('.question-block');
let current = 0;

// SHOW QUESTION
function showQuestion(index) {
    questions.forEach(q => q.classList.add('hidden'));
    questions[index].classList.remove('hidden');

    document.querySelectorAll('[id^="nav-"]').forEach(btn => {
        btn.classList.remove('bg-blue-500','text-white');
    });

    let id = questions[index].id.split('-')[1];
    document.getElementById('nav-'+id).classList.add('bg-blue-500','text-white');
}
showQuestion(current);

// NEXT / PREV
function nextQ() {
    if (current < questions.length - 1) {
        current++;
        showQuestion(current);
    }
}
function prevQ() {
    if (current > 0) {
        current--;
        showQuestion(current);
    }
}

// NAV CLICK
function goToQ(index) {
    current = index;
    showQuestion(current);
}

// MARK
function markReview(id) {
    document.getElementById('nav-'+id).classList.add('bg-yellow-400');
}

// SAVE ANSWERS
document.querySelectorAll('.answer-option').forEach(input => {

    input.addEventListener('change', () => {
        localStorage.setItem(input.name, input.value);
        document.getElementById('nav-'+input.dataset.id).classList.add('bg-green-400');
        updateProgress();
    });

    let saved = localStorage.getItem(input.name);
    if (saved == input.value) {
        input.checked = true;
        document.getElementById('nav-'+input.dataset.id).classList.add('bg-green-400');
    }
});

// PROGRESS
function updateProgress() {
    let answered = document.querySelectorAll('.answer-option:checked').length;
    let total = questions.length;
    let percent = Math.round((answered / total) * 100);

    document.getElementById('progressText').innerText = percent + '%';
    document.getElementById('progressBar').style.width = percent + '%';
}
updateProgress();
</script>

<script>
// TIMER
let totalTime = {{ $quiz->duration * 60 }};
let timer = document.getElementById('timer');

function formatTime(sec) {
    let m = Math.floor(sec / 60);
    let s = sec % 60;
    return m + ":" + (s < 10 ? '0' : '') + s;
}

function updateTimer() {

    timer.innerHTML = formatTime(totalTime);

    if (totalTime <= 30) {
        timer.classList.add('bg-yellow-500');
    }

    if (totalTime === 10) {
        alert("⚠ Only 10 seconds left!");
    }

    if (totalTime <= 0) {
        alert("⏰ Time's up!");
        localStorage.clear();
        document.getElementById('quizForm').submit();
    }

    totalTime--;
}

setInterval(updateTimer, 1000);
updateTimer();
</script>

<script>
// ANTI CHEAT
document.addEventListener("visibilitychange", function() {
    if (document.hidden) {
        alert("⚠ Don't switch tabs!");
    }
});

// PREVENT REFRESH
window.onbeforeunload = function() {
    return "Your progress will be lost!";
};
</script>

@endsection