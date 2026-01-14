<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Student - @yield('title')</title>
</head>
<body class="bg-gray-50 flex h-screen overflow-hidden font-sans">
    
    @include('layouts.partials.sidebars.student')

    <main class="flex-1 min-w-0 overflow-y-auto flex flex-col">
        <header class="px-8 bg-white border-b border-gray-200 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">@yield('title')</h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-500 uppercase font-bold tracking-tight">Student</span>
                <div class="h-8 w-8 rounded-full bg-emerald-600 flex items-center justify-center text-white font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <div class="p-8 flex-1">
            @yield('content')
        </div>
    </main>
</body>
</html>