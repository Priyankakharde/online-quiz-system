@extends('layouts.app')

@section('content')

<div class="p-6 flex justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-2xl">

        <!-- TITLE -->
        <h2 class="text-3xl font-bold mb-6 text-blue-600 flex items-center gap-2">
            ➕ Create New Exam
        </h2>

        <!-- SUCCESS -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- ERRORS -->
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
        <form action="{{ route('quizzes.store') }}" method="POST">
            @csrf

            <!-- TITLE -->
            <div class="mb-5">
                <label class="font-semibold">Exam Title</label>
                <input type="text"
                       name="title"
                       value="{{ old('title') }}"
                       placeholder="Enter exam title"
                       class="w-full border p-2 rounded focus:ring focus:ring-blue-200"
                       required>
            </div>

            <!-- DESCRIPTION -->
            <div class="mb-5">
                <label class="font-semibold">Description</label>
                <textarea name="description"
                          placeholder="Optional description"
                          class="w-full border p-2 rounded focus:ring focus:ring-blue-200">{{ old('description') }}</textarea>
            </div>

            <!-- CATEGORY -->
            <div class="mb-5">
                <label class="font-semibold">Select Category</label>

                <select name="category_id"
                        class="w-full border p-2 rounded focus:ring focus:ring-blue-200"
                        required>

                    <option value="">-- Select Category --</option>

                    @forelse($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @empty
                        <option disabled>⚠ No categories found</option>
                    @endforelse

                </select>
            </div>

            <!-- DATE -->
            <div class="mb-5">
                <label class="font-semibold">Exam Date & Time</label>

                <input type="datetime-local"
                       id="exam_date"
                       name="exam_date"
                       value="{{ old('exam_date') }}"
                       class="w-full border p-2 rounded focus:ring focus:ring-blue-200"
                       required>

                <p class="text-sm text-gray-500 mt-1">
                    ⏰ Tip: Set current time to test "Start Quiz"
                </p>
            </div>

            <!-- DURATION -->
            <div class="mb-5">
                <label class="font-semibold">Duration (Minutes)</label>
                <input type="number"
                       name="duration"
                       value="{{ old('duration', 60) }}"
                       min="1"
                       placeholder="e.g. 60"
                       class="w-full border p-2 rounded focus:ring focus:ring-blue-200"
                       required>
            </div>

            <!-- BUTTON -->
            <div class="flex justify-between items-center mt-6">

                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                    💾 Save Exam
                </button>

                <a href="{{ route('quizzes.index') }}"
                   class="text-gray-600 hover:underline">
                    ← Back
                </a>

            </div>

        </form>

    </div>

</div>

<!-- 🔥 IMPORTANT SCRIPT (AUTO TIME FIX) -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    let input = document.getElementById('exam_date');

    // If empty → auto set current time + 1 min
    if (!input.value) {
        let now = new Date();
        now.setMinutes(now.getMinutes() + 1);

        let formatted = now.toISOString().slice(0,16);
        input.value = formatted;
    }

});
</script>

@endsection