<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Student - @yield('title')</title>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden font-sans" x-data="{ sidebarOpen: false }">

    {{-- Mobile Overlay --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
        x-transition:enter="transition-opacity ease-linear duration-300" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="transition-opacity ease-linear duration-300" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden backdrop-blur-sm" 
        style="display: none;">
    </div>

    {{-- Sidebar Include --}}
    @include('layouts.partials.sidebars.student')

    <main class="flex-1 min-w-0 overflow-y-auto flex flex-col bg-gray-100">

        {{-- Top Navigation Header --}}
        <header class="px-8 lg:px-16 bg-white border-b border-gray-200 py-4 flex justify-between items-center sticky top-0 z-10 shrink-0">
            
            {{-- Mobile Sidebar Toggle --}}
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-xl font-bold text-gray-800">@yield('title')</h1>
            </div>

            {{-- User Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" class="flex items-center space-x-3 focus:outline-none group">
                    <span class="text-sm text-gray-500 hidden sm:block group-hover:text-gray-700 transition">Student</span>
                    <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold shadow-sm overflow-hidden border border-indigo-200">
                        @if (auth()->user()->profile_image)
                            <img src="{{ auth()->user()->profile_image_url }}" class="h-full w-full object-cover">
                        @else
                            {{ substr(auth()->user()->name, 0, 1) }}
                        @endif
                    </div>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="open" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50"
                    style="display: none;">

                    <div class="px-4 py-2 border-b border-gray-50 mb-1">
                        <p class="text-xs text-gray-400 uppercase font-semibold">Account Settings</p>
                    </div>

                    {{-- 1. My Profile --}}
                    <a href="{{ route('student.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        My Profile
                    </a>

                    <hr class="my-1 border-gray-100">

                    {{-- 2. NEW: Delete Account Link --}}
                    <a href="{{ route('account.delete.show') }}" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete Account
                    </a>

                    <hr class="my-1 border-gray-100">

                    {{-- 3. Sign Out --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button onclick="return confirm('Are you sure you want to logout?')" type="submit" class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <div class="p-4 lg:p-8 flex-1">
            @yield('content')
        </div>

        {{-- Footer --}}
        <footer class="px-8 py-4 text-xs text-gray-400 text-center border-t border-gray-200 bg-white/50">
            &copy; 2026 ExamSystem Student Portal
        </footer>
    </main>

</body>
</html>