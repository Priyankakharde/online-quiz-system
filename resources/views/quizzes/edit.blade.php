@extends('layouts.app')

@section('content')

<div class="p-6 flex justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-4xl">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 class="text-2xl font-bold text-indigo-600">
                ✏️ Edit Exam
            </h2>

            @php
                $now = now();
                $start = $quiz->exam_date ? \Carbon\Carbon::parse($quiz->exam_date) : null;
                $end = $start ? $start->copy()->addMinutes($quiz->duration ?? 0) : null;

                if (!$start) $status = 'Draft';
                elseif ($now < $start) $status = 'Upcoming';
                elseif ($now >= $start && $now <= $end) $status = 'Ongoing';
                else $status = 'Completed';
            @endphp

            <span class="px-3 py-1 text-xs rounded-full text-white font-semibold
                {{ $status=='Ongoing' ? 'bg-green-500' :
                   ($status=='Upcoming' ? 'bg-yellow-500' :
                   ($status=='Completed' ? 'bg-gray-500' : 'bg-blue-500')) }}">
                {{ $status }}
            </span>
        </div>

        <!-- SUCCESS -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- ERROR -->
        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>⚠ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM -->
        <form method="POST" action="{{ route('quizzes.update', $quiz->id) }}">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 gap-6">

                <!-- TITLE -->
                <div>
                    <label class="font-semibold">Exam Title</label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title', $quiz->title) }}"
                           class="w-full border p-2 rounded mt-1
                           @error('title') border-red-500 @enderror"
                           required>
                </div>

                <!-- CATEGORY -->
                <div>
                    <label class="font-semibold">Category</label>
                    <select name="category_id"
                            class="w-full border p-2 rounded mt-1"
                            required>
                        <option value="">-- Select --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $quiz->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- DATE -->
                <div>
                    <label class="font-semibold">Exam Date</label>
                    <input type="datetime-local"
                           name="exam_date"
                           id="exam_date"
                           value="{{ $quiz->exam_date ? \Carbon\Carbon::parse($quiz->exam_date)->format('Y-m-d\TH:i') : '' }}"
                           class="w-full border p-2 rounded mt-1">
                </div>

                <!-- DURATION -->
                <div>
                    <label class="font-semibold">Duration (mins)</label>
                    <input type="number"
                           name="duration"
                           id="duration"
                           value="{{ $quiz->duration }}"
                           class="w-full border p-2 rounded mt-1"
                           required>
                </div>

            </div>

            <!-- DESCRIPTION -->
            <div class="mt-6">
                <label class="font-semibold">Description</label>
                <textarea name="description"
                          id="description"
                          rows="3"
                          class="w-full border p-2 rounded mt-1">{{ $quiz->description }}</textarea>
            </div>

            <!-- LIVE PREVIEW -->
            <div class="bg-gray-100 p-4 rounded mt-6 text-sm">
                <h4 class="font-semibold mb-2">📊 Preview</h4>

                <p><b>Title:</b> <span id="previewTitle">{{ $quiz->title }}</span></p>
                <p><b>Duration:</b> <span id="previewDuration">{{ $quiz->duration }}</span> mins</p>
                <p id="previewDate"></p>
            </div>

            <!-- BUTTONS -->
            <div class="flex justify-between mt-6">
                <button class="bg-indigo-600 text-white px-6 py-2 rounded">
                    Update Exam
                </button>

                <a href="{{ route('quizzes.index') }}" class="text-gray-600">
                    ← Back
                </a>
            </div>

        </form>

    </div>

</div>

<!-- LIVE PREVIEW SCRIPT -->
<script>
    document.getElementById('title').oninput = e =>
        document.getElementById('previewTitle').innerText = e.target.value;

    document.getElementById('duration').oninput = e =>
        document.getElementById('previewDuration').innerText = e.target.value;

    document.getElementById('exam_date').oninput = e =>
        document.getElementById('previewDate').innerText =
            e.target.value ? "📅 " + e.target.value : "";
</script>

@endsection