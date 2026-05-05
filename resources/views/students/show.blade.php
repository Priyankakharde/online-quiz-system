@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">👤 Student Profile</h2>

        <div class="flex gap-2">
            <a href="{{ route('students.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">
                ← Back
            </a>

            <a href="{{ route('students.edit', $student->id) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                ✏ Edit
            </a>
        </div>
    </div>

    <!-- PROFILE CARD -->
    <div class="bg-white p-6 rounded-xl shadow mb-6 flex items-center gap-4">

        <!-- AVATAR -->
        <div class="w-14 h-14 flex items-center justify-center bg-blue-500 text-white rounded-full text-xl font-bold">
            {{ strtoupper(substr($student->name, 0, 1)) }}
        </div>

        <!-- INFO -->
        <div>
            <h3 class="text-lg font-bold text-gray-700">
                {{ $student->name }}
            </h3>
            <p class="text-gray-500 text-sm">
                {{ $student->email }}
            </p>
        </div>

    </div>

    <!-- STATS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 text-center">

        <div class="bg-blue-100 p-4 rounded-xl">
            <p class="text-sm text-gray-600">Attempts</p>
            <p class="text-xl font-bold text-blue-600">{{ $totalAttempts }}</p>
        </div>

        <div class="bg-green-100 p-4 rounded-xl">
            <p class="text-sm text-gray-600">Average</p>
            <p class="text-xl font-bold text-green-600">{{ round($avgScore,2) }}%</p>
        </div>

        <div class="bg-purple-100 p-4 rounded-xl">
            <p class="text-sm text-gray-600">Highest</p>
            <p class="text-xl font-bold text-purple-600">{{ $highestScore }}%</p>
        </div>

        <div class="bg-yellow-100 p-4 rounded-xl">
            <p class="text-sm text-gray-600">Rank</p>
            <p class="text-xl font-bold text-yellow-600">#{{ $rank ?? '-' }}</p>
        </div>

    </div>

    <!-- PASS / FAIL -->
    <div class="grid grid-cols-2 gap-4 mb-6 text-center">

        <div class="bg-green-100 p-4 rounded-xl">
            <p class="text-sm text-gray-600">Passed</p>
            <p class="text-xl font-bold text-green-600">{{ $passed }}</p>
        </div>

        <div class="bg-red-100 p-4 rounded-xl">
            <p class="text-sm text-gray-600">Failed</p>
            <p class="text-xl font-bold text-red-600">{{ $failed }}</p>
        </div>

    </div>

    <!-- PROGRESS BAR -->
    <div class="mb-6">
        <p class="text-sm text-gray-600 mb-1">Performance</p>

        <div class="w-full bg-gray-200 rounded h-3">
            <div class="h-3 rounded bg-blue-500"
                 style="width: {{ $avgScore }}%">
            </div>
        </div>
    </div>

    <!-- RECENT RESULTS -->
    <div class="bg-white rounded-xl shadow">

        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-700">📊 Recent Results</h3>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Quiz</th>
                        <th class="p-3">Score</th>
                        <th class="p-3">%</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Date</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($recentResults as $result)

                    <tr class="border-t text-center hover:bg-gray-50">

                        <td class="p-3 text-left">
                            {{ $result->quiz->title ?? 'N/A' }}
                        </td>

                        <td class="p-3">
                            {{ $result->score }}/{{ $result->total }}
                        </td>

                        <td class="p-3 font-semibold">
                            {{ round($result->percentage,2) }}%
                        </td>

                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-white text-xs
                                {{ $result->percentage >= 40 ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $result->percentage >= 40 ? 'Pass' : 'Fail' }}
                            </span>
                        </td>

                        <td class="p-3 text-gray-500 text-xs">
                            {{ $result->created_at->format('d M Y') }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-500">
                            🚫 No attempts yet
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection