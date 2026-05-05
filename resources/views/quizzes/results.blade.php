@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <!-- PAGE HEADER -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">📊 Quiz Result</h2>
        <p class="text-gray-500 text-sm">Check your performance below</p>
    </div>

    <!-- RESULT CARD -->
    @if(isset($myResult) && $myResult)

    <div class="bg-white p-6 rounded-xl shadow mb-8">

        <!-- QUIZ TITLE -->
        <h3 class="text-lg font-semibold mb-4 text-gray-700">
            {{ $quiz->title ?? 'Quiz' }}
        </h3>

        <!-- STATS -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            <!-- Score -->
            <div class="bg-blue-100 p-4 rounded text-center">
                <p class="text-sm text-gray-600">Score</p>
                <h2 class="text-xl font-bold text-blue-600">
                    {{ $myResult->score ?? 0 }} / {{ $myResult->total ?? 0 }}
                </h2>
            </div>

            <!-- Percentage -->
            <div class="bg-green-100 p-4 rounded text-center">
                <p class="text-sm text-gray-600">Percentage</p>
                <h2 class="text-xl font-bold text-green-600">
                    {{ $myResult->percentage ?? 0 }}%
                </h2>
            </div>

            <!-- Grade -->
            <div class="bg-yellow-100 p-4 rounded text-center">
                <p class="text-sm text-gray-600">Grade</p>
                <h2 class="text-xl font-bold text-yellow-600">
                    {{ method_exists($myResult, 'grade') ? $myResult->grade() : '-' }}
                </h2>
            </div>

            <!-- Rank -->
            <div class="bg-purple-100 p-4 rounded text-center">
                <p class="text-sm text-gray-600">Rank</p>
                <h2 class="text-xl font-bold text-purple-600">
                    #{{ $myResult->rank ?? '-' }}
                </h2>
            </div>

        </div>

        <!-- PERFORMANCE -->
        <div class="mt-6 text-center">
            <p class="text-lg font-semibold text-gray-700">
                🎯 Performance: {{ $myResult->performance ?? 'N/A' }}
            </p>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="mt-6 flex flex-wrap gap-3 justify-center">

            <a href="{{ route('certificate.download', $myResult->id ?? 0) }}"
               class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">
                📄 Certificate
            </a>

            <a href="{{ route('leaderboard') }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">
                🏆 Leaderboard
            </a>

            <a href="{{ route('dashboard') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                🏠 Back to Dashboard
            </a>

        </div>

    </div>

    @endif


    <!-- LEADERBOARD TABLE -->
    <div class="bg-white p-6 rounded-xl shadow">

        <h3 class="text-lg font-semibold mb-4 text-gray-700">
            🏆 Leaderboard
        </h3>

        <div class="overflow-x-auto">

            <table class="w-full border text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Rank</th>
                        <th class="p-3">Student</th>
                        <th class="p-3">Score</th>
                        <th class="p-3">Percentage</th>
                        <th class="p-3">Grade</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($leaderboard ?? [] as $result)

                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3 font-semibold">#{{ $result->rank }}</td>
                            <td class="p-3">{{ $result->user->name ?? 'User' }}</td>
                            <td class="p-3">{{ $result->score }}/{{ $result->total }}</td>
                            <td class="p-3">{{ $result->percentage }}%</td>
                            <td class="p-3">
                                {{ method_exists($result, 'grade') ? $result->grade() : '-' }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                No results yet
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection