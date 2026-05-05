<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Quiz Exam</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#0b1a2f] text-white p-5">

        <h2 class="text-xl font-bold mb-8">Online Quiz Exam</h2>

        <nav class="space-y-2 text-sm">

            <a href="{{ url('/dashboard') }}"
               class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->is('dashboard') ? 'bg-gray-700' : '' }}">
               📊 Dashboard
            </a>

            <a href="{{ url('/admin/quizzes') }}"
               class="block px-3 py-2 rounded hover:bg-gray-700">
               📝 Exams
            </a>

            <a href="{{ url('/admin/categories') }}"
               class="block px-3 py-2 rounded hover:bg-gray-700">
               📂 Categories
            </a>

            <a href="{{ url('/admin/subjects') }}"
               class="block px-3 py-2 rounded hover:bg-gray-700">
               📚 Subjects
            </a>

            <a href="{{ url('/admin/questions') }}"
               class="block px-3 py-2 rounded hover:bg-gray-700">
               ❓ Questions
            </a>

            <a href="{{ url('/admin/students') }}"
               class="block px-3 py-2 rounded hover:bg-gray-700">
               👩‍🎓 Students
            </a>

            <a href="{{ url('/admin/results') }}"
               class="block px-3 py-2 rounded hover:bg-gray-700">
               📊 Results
            </a>

            <a href="{{ url('/leaderboard') }}"
               class="block px-3 py-2 rounded hover:bg-gray-700">
               🏆 Leaderboard
            </a>

        </nav>

    </aside>


    <!-- MAIN CONTENT -->
    <div class="flex-1 p-6">

        <!-- TOP BAR -->
        <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow mb-6">

            <h1 class="text-xl font-bold text-gray-700">
                Online Quiz Dashboard
            </h1>

            <div class="flex items-center gap-4">

                @auth
                    <span class="font-semibold text-gray-700">
                        {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded">
                            Logout
                        </button>
                    </form>
                @endauth

            </div>

        </div>

        <!-- FLASH MESSAGE -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- PAGE CONTENT -->
        @yield('content')

    </div>

</div>

</body>
</html>