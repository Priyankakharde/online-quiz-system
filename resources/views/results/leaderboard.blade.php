@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">🏆 Leaderboard</h2>

        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-sm">
            Total: {{ count($results) }}
        </span>
    </div>


    <!-- FILTER -->
    <form method="GET" class="mb-4 flex gap-2">
        <select name="quiz_id" onchange="this.form.submit()"
            class="border p-2 rounded w-64">

            <option value="">-- All Quizzes --</option>

            @foreach($quizzes as $quiz)
                <option value="{{ $quiz->id }}"
                    {{ request('quiz_id') == $quiz->id ? 'selected' : '' }}>
                    {{ $quiz->title }}
                </option>
            @endforeach

        </select>
    </form>


    <!-- TOP 3 -->
    @if(count($results) >= 1)
    <div class="grid grid-cols-3 gap-4 mb-6 text-center">

        @foreach($results->take(3) as $i => $top)

        <div class="p-4 rounded shadow
            {{ $i == 0 ? 'bg-yellow-100' : ($i == 1 ? 'bg-gray-100' : 'bg-orange-100') }}">

            <h3 class="text-lg font-bold">
                {{ $top->user->name }}
            </h3>

            <p class="text-sm text-gray-600">
                {{ $top->quiz->title }}
            </p>

            <p class="text-xl font-bold mt-2">
                {{ $top->percentage }}%
            </p>

            <p class="text-xs mt-1">
                {{ $i == 0 ? '🥇 1st' : ($i == 1 ? '🥈 2nd' : '🥉 3rd') }}
            </p>

        </div>

        @endforeach

    </div>
    @endif


    <!-- TABLE -->
    <div class="bg-white shadow rounded overflow-x-auto">

        <table class="w-full text-sm">

            <!-- HEAD -->
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="p-3">Rank</th>
                    <th class="p-3 text-left">Student</th>
                    <th class="p-3 text-left">Quiz</th>
                    <th class="p-3">Score</th>
                    <th class="p-3">%</th>
                    <th class="p-3">Grade</th>
                    <th class="p-3">Status</th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody>

            @forelse($results as $result)

                <tr class="text-center border-t hover:bg-gray-50">

                    <!-- RANK -->
                    <td class="p-3 font-bold">
                        {{ $result->rank ?? $loop->iteration }}
                    </td>

                    <!-- NAME -->
                    <td class="p-3 text-left font-semibold text-blue-600">
                        {{ $result->user->name }}
                    </td>

                    <!-- QUIZ -->
                    <td class="p-3 text-left text-gray-600">
                        {{ $result->quiz->title }}
                    </td>

                    <!-- SCORE -->
                    <td class="p-3 font-bold text-green-600">
                        {{ $result->score }} / {{ $result->total }}
                    </td>

                    <!-- PERCENTAGE -->
                    <td class="p-3">
                        {{ $result->percentage }}%
                    </td>

                    <!-- GRADE -->
                    <td class="p-3 font-bold">
                        {{ $result->grade() }}
                    </td>

                    <!-- STATUS -->
                    <td class="p-3">
                        <span class="px-2 py-1 rounded text-white text-xs
                            {{ $result->isPassed() ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ $result->isPassed() ? 'Passed' : 'Failed' }}
                        </span>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7" class="text-center p-6 text-gray-500">
                        🚫 No results found
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection