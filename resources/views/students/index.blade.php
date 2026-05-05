@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold">👩‍🎓 Students</h2>
            <p class="text-sm text-gray-500">
                Total Students: <span class="font-semibold text-blue-600">{{ $totalStudents }}</span>
            </p>
        </div>

        <a href="{{ route('students.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
           + Add Student
        </a>
    </div>

    <!-- SUCCESS / ERROR -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- SEARCH -->
    <form method="GET" class="mb-6 flex gap-2">
        <input type="text" name="search" value="{{ $search ?? '' }}"
            placeholder="🔍 Search name or email..."
            class="border p-2 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-400">

        <button class="bg-gray-700 hover:bg-gray-800 text-white px-4 rounded">
            Search
        </button>

        @if($search)
            <a href="{{ route('students.index') }}"
               class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
               Clear
            </a>
        @endif
    </form>

    <!-- TABLE -->
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-3">#</th>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3">Attempts</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>

            <tbody>

            @forelse($students as $i => $student)

                <tr class="border-t hover:bg-gray-50 transition text-center">

                    <!-- INDEX -->
                    <td class="p-3">
                        {{ ($students->currentPage() - 1) * $students->perPage() + $i + 1 }}
                    </td>

                    <!-- NAME -->
                    <td class="p-3 text-left font-semibold text-blue-600">
                        {{ $student->name }}
                    </td>

                    <!-- EMAIL -->
                    <td class="p-3 text-left text-gray-600">
                        {{ $student->email }}
                    </td>

                    <!-- ATTEMPTS -->
                    <td class="p-3">
                        <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-xs">
                            {{ $student->results_count }}
                        </span>
                    </td>

                    <!-- STATUS -->
                    <td class="p-3">
                        @if($student->results_count > 0)
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs">
                                Active
                            </span>
                        @else
                            <span class="bg-gray-200 text-gray-600 px-2 py-1 rounded text-xs">
                                New
                            </span>
                        @endif
                    </td>

                    <!-- ACTIONS -->
                    <td class="p-3 flex justify-center gap-2 flex-wrap">

                        <!-- VIEW -->
                        <a href="{{ route('students.show', $student->id) }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                           👁 View
                        </a>

                        <!-- EDIT -->
                        <a href="{{ route('students.edit', $student->id) }}"
                           class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                           ✏ Edit
                        </a>

                        <!-- DELETE -->
                        <form method="POST"
                              action="{{ route('students.destroy', $student->id) }}"
                              onsubmit="return confirm('Are you sure you want to delete this student?')">
                            @csrf
                            @method('DELETE')

                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                🗑 Delete
                            </button>
                        </form>

                    </td>

                </tr>

            @empty

                <!-- EMPTY STATE -->
                <tr>
                    <td colspan="6" class="p-10 text-center text-gray-500">
                        🚫 No students found.<br>
                        <a href="{{ route('students.create') }}" class="text-blue-500 underline">
                            Add your first student
                        </a>
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $students->links() }}
    </div>

</div>

@endsection