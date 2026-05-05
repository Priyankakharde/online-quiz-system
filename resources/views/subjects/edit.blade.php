@extends('layouts.app')

@section('content')

<div class="p-6">

    <h2 class="text-xl font-bold mb-4">Edit Subject</h2>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="bg-red-500 text-white p-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Select Quiz -->
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Select Exam</label>
            <select name="quiz_id" class="w-full border p-2 rounded" required>
                @foreach($quizzes as $quiz)
                    <option value="{{ $quiz->id }}" 
                        {{ $subject->quiz_id == $quiz->id ? 'selected' : '' }}>
                        {{ $quiz->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Subject Name -->
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Subject Name</label>
            <input type="text" name="name" 
                   value="{{ $subject->name }}" 
                   class="w-full border p-2 rounded" required>
        </div>

        <!-- Buttons -->
        <div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                Update Subject
            </button>

            <a href="{{ route('subjects.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">
                Back
            </a>
        </div>

    </form>

</div>

@endsection