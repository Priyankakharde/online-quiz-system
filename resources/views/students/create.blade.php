@extends('layouts.app')

@section('content')

<div class="p-6 flex justify-center">

    <div class="w-full max-w-lg bg-white rounded-xl shadow p-6">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-700">
                ➕ Create Student
            </h2>

            <a href="{{ route('students.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">
               ← Back
            </a>
        </div>

        <!-- GLOBAL ERROR -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="text-sm list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM -->
        <form method="POST" action="{{ route('students.store') }}" class="space-y-4">
            @csrf

            <!-- NAME -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Name
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">

                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">

                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Password
                </label>

                <div class="relative">
                    <input type="password" id="password" name="password"
                        class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">

                    <button type="button"
                        onclick="togglePassword()"
                        class="absolute right-2 top-2 text-sm text-gray-500">
                        👁
                    </button>
                </div>

                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- SUBMIT -->
            <button
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded shadow">
                Create Student
            </button>

        </form>

    </div>

</div>

<!-- PASSWORD TOGGLE SCRIPT -->
<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

@endsection