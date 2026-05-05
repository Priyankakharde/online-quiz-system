@extends('layouts.app')

@section('content')

<div style="padding:20px;">

    <h2>✏ Edit Quiz</h2>

    <a href="{{ route('quizzes.index') }}" 
       style="background:gray; color:white; padding:6px; text-decoration:none;">
       ← Back
    </a>

    <br><br>

    <form method="POST" action="{{ route('quizzes.update', $quiz->id) }}">

        @csrf
        @method('PUT')

        <!-- Quiz Title -->
        <label>Quiz Title:</label><br>
        <input type="text" name="title" value="{{ $quiz->title }}" required 
               style="width:300px; padding:5px;"><br><br>

        <!-- Time Limit -->
        <label>Time Limit (minutes):</label><br>
        <input type="number" name="time_limit" value="{{ $quiz->time_limit }}" required 
               style="width:300px; padding:5px;"><br><br>

        <!-- Total Questions -->
        <label>Total Questions:</label><br>
        <input type="number" name="total_questions" value="{{ $quiz->total_questions }}" 
               style="width:300px; padding:5px;"><br><br>

        <!-- Category -->
        <label>Select Category:</label><br>
        <select name="category_id" required style="width:300px; padding:5px;">
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" 
                    {{ $quiz->category_id == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <br><br>

        <button style="background:orange; color:white; padding:8px;">
            Update Quiz
        </button>

    </form>

</div>

@endsection