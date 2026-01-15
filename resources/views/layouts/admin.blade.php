<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <title>Admin - @yield('title')</title>
</head>
<body class="bg-gray-100 flex h-screen overflow-hidden font-sans">

    {{-- Only include the sidebar here --}}
    @include('layouts.partials.sidebars.admin')

    <main class="flex-1 min-w-0 overflow-y-auto flex flex-col bg-gray-100">
        {{-- Sticky Header --}}
        <header class="px-8 lg:px-16 bg-white border-b border-gray-200 py-4 flex justify-between items-center sticky top-0 z-10 shrink-0">
            <h1 class="text-xl font-bold text-gray-800">@yield('title')</h1>

            {{-- Profile Dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false"
                    class="flex items-center space-x-3 focus:outline-none group">
                    <span class="text-sm text-gray-500 hidden sm:block group-hover:text-gray-700 transition">Administrator</span>
                    <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold shadow-sm overflow-hidden border border-indigo-200">
                        @if (auth()->user()->profile_image)
                            <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" class="h-full w-full object-cover">
                        @else
                            {{ substr(auth()->user()->name, 0, 1) }}
                        @endif
                    </div>
                </button>

                <div x-show="open" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50"
                    style="display: none;">

                    <div class="px-4 py-2 border-b border-gray-50 mb-1">
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Account Settings</p>
                    </div>

                    <a href="{{ route('admin.profile') }}"
                        class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        My Profile
                    </a>
                    <hr class="my-1 border-gray-100">

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button onclick="return confirm('Are you sure you want to sign out?')" type="submit"
                            class="w-full flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition font-bold">
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
        <div class="p-8 flex-1">
            @yield('content')
        </div>

        <footer class="px-8 py-4 text-[10px] uppercase font-bold tracking-widest text-gray-400 text-center border-t border-gray-200 bg-white/50">
            &copy; 2026 ExamSystem Admin Portal
        </footer>
    </main>
</body>
</html>