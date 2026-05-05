@extends('layouts.app')

@section('content')

<div class="p-6">

    <h2 class="text-2xl font-bold mb-4">Results</h2>

    <div class="bg-white shadow rounded overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead class="bg-gray-200">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3">Student</th>
                    <th class="p-3">Quiz</th>
                    <th class="p-3">Score</th>
                    <th class="p-3">Percentage</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($results as $index => $result)

                    @php
                        $percentage = $result->percentage();
                    @endphp

                    <tr class="border-t">

                        <td class="p-3">{{ $index + 1 }}</td>

                        <td class="p-3">
                            {{ $result->user->name ?? 'N/A' }}
                        </td>

                        <td class="p-3">
                            {{ $result->quiz->title ?? 'N/A' }}
                        </td>

                        <td class="p-3 font-bold">
                            {{ $result->score }}
                        </td>

                        <td class="p-3">
                            {{ $percentage }}%
                        </td>

                        <td class="p-3">
                            @if($result->isPassed())
                                <span class="bg-green-500 text-white px-2 py-1 rounded text-sm">
                                    Passed
                                </span>
                            @else
                                <span class="bg-red-500 text-white px-2 py-1 rounded text-sm">
                                    Failed
                                </span>
                            @endif
                        </td>

                        <td class="p-3 flex gap-2">

                            <!-- View -->
                            <a href="{{ route('results.show', $result->id) }}"
                               class="bg-blue-500 text-white px-2 py-1 rounded text-sm">
                                View
                            </a>

                            <!-- Certificate -->
                            <a href="{{ route('certificate.download', $result->id) }}"
                               class="bg-purple-500 text-white px-2 py-1 rounded text-sm">
                                🎓 Certificate
                            </a>

                            <!-- Delete -->
                            <form action="{{ route('results.destroy', $result->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-600 text-white px-2 py-1 rounded text-sm">
                                    Delete
                                </button>
                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">
                            No results found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection