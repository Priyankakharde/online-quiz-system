@extends('layouts.app')

@section('content')

<div class="p-6 flex justify-center">

    <div class="bg-white p-8 rounded shadow w-full max-w-6xl">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">

            <div>
                <h2 class="text-2xl font-bold text-gray-700">
                    📊 Quiz Result
                </h2>
                <p class="text-sm text-gray-500">
                    {{ $quiz->title }}
                </p>
            </div>

            <div class="text-right">
                <p class="font-semibold">{{ auth()->user()->name }}</p>
                <span class="text-xs bg-gray-200 px-2 py-1 rounded">
                    Student
                </span>
            </div>

        </div>

        {{-- ===================== MY RESULT ===================== --}}
        @if(isset($myResult))

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8 text-center">

            <div class="bg-blue-100 p-5 rounded shadow">
                <p class="text-sm text-gray-600">Score</p>
                <p class="text-2xl font-bold text-blue-600">
                    {{ $myResult->score }} / {{ $myResult->total }}
                </p>
            </div>

            <div class="bg-green-100 p-5 rounded shadow">
                <p class="text-sm text-gray-600">Percentage</p>
                <p class="text-2xl font-bold text-green-600">
                    {{ $myResult->percentage }}%
                </p>
            </div>

            <div class="bg-purple-100 p-5 rounded shadow">
                <p class="text-sm text-gray-600">Grade</p>
                <p class="text-2xl font-bold text-purple-600">
                    {{ $myResult->grade() }}
                </p>
            </div>

            <div class="bg-yellow-100 p-5 rounded shadow">
                <p class="text-sm text-gray-600">Rank</p>
                <p class="text-2xl font-bold text-yellow-600">
                    #{{ $myResult->rank }}
                </p>
            </div>

        </div>

        <!-- PROGRESS BAR -->
        <div class="mb-6">
            <div class="w-full bg-gray-200 rounded h-4">
                <div class="h-4 rounded 
                    {{ $myResult->isPassed() ? 'bg-green-500' : 'bg-red-500' }}"
                    style="width: {{ $myResult->percentage }}%">
                </div>
            </div>
        </div>

        <!-- STATUS -->
        <div class="text-center mb-6">
            <div class="px-6 py-2 rounded inline-block text-white font-semibold
                {{ $myResult->isPassed() ? 'bg-green-500' : 'bg-red-500' }}">
                {{ strtoupper($myResult->status) }}
            </div>
        </div>

        <!-- PERFORMANCE -->
        <div class="text-center text-gray-600 mb-6">
            🎯 Performance: <b>{{ $myResult->performance }}</b>
        </div>

        <!-- ACTIONS -->
        <div class="flex justify-center gap-4 mb-10 flex-wrap">

            <a href="{{ route('certificate.download', $myResult->id) }}"
               class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded shadow">
                🎓 Download Certificate
            </a>

            <a href="{{ route('leaderboard') }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded">
                🏆 Leaderboard
            </a>

            <a href="{{ route('dashboard') }}"
               class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded">
                🏠 Dashboard
            </a>

        </div>

        @endif


        {{-- ===================== LEADERBOARD ===================== --}}
        <div class="mt-6">

            <h3 class="text-lg font-bold mb-4 text-gray-700">
                🏆 Leaderboard
            </h3>

            <div class="overflow-x-auto">

                <table class="w-full border text-sm text-left">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 border">Rank</th>
                            <th class="p-3 border">Student</th>
                            <th class="p-3 border">Score</th>
                            <th class="p-3 border">Percentage</th>
                            <th class="p-3 border">Grade</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($leaderboard as $result)

                        <tr class="
                            {{ $result->user_id == auth()->id() ? 'bg-blue-50 font-semibold' : '' }}
                        ">

                            <td class="p-3 border">
                                #{{ $result->rank }}
                            </td>

                            <td class="p-3 border">
                                {{ $result->user->name }}
                            </td>

                            <td class="p-3 border">
                                {{ $result->score }}/{{ $result->total }}
                            </td>

                            <td class="p-3 border">
                                {{ $result->percentage }}%
                            </td>

                            <td class="p-3 border">
                                {{ $result->grade() }}
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="5" class="text-center p-4 text-gray-500">
                                No results yet
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection