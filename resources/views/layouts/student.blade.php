<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title') - Student Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { -webkit-tap-highlight-color: transparent; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ mobileMenuOpen: false }">

    <div class="min-h-screen flex flex-col md:flex-row">
        
        {{-- 1. MOBILE TOP NAV (Visible only on Mobile) --}}
        <header class="md:hidden bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-40 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">E</div>
                <span class="font-bold text-gray-800 tracking-tight">ExamSystem</span>
            </div>
            
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg bg-gray-50 text-gray-600 active:bg-gray-200 transition-colors">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </header>

        {{-- 2. SIDEBAR (Responsive) --}}
        {{-- Hidden on mobile unless toggled; Always visible on MD+ --}}
        <aside 
            x-cloak
            :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0 md:h-screen sticky top-0">
            
            <div class="flex flex-col h-full p-6">
                <div class="hidden md:flex items-center gap-3 mb-10 px-2">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-100">E</div>
                    <span class="font-bold text-xl text-gray-800 tracking-tight">ExamSystem</span>
                </div>

                <nav class="flex-1 space-y-2">
                    <a href="{{ route('student.dashboard') }}" 
                       class="flex items-center gap-3 p-3.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('student.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('student.exams.index') }}" 
                       class="flex items-center gap-3 p-3.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('student.exams.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Available Exams
                    </a>

                    <a href="{{ route('student.results.index') }}" 
                       class="flex items-center gap-3 p-3.5 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('student.results.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        My Results
                    </a>
                </nav>

                <div class="pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-3 px-2 mb-4">
                        <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden">
                            <img src="{{ auth()->user()->profile_image ? asset('storage/'.auth()->user()->profile_image) : 'https://ui-avatars.com/api/?name='.auth()->user()->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-gray-400 truncate uppercase">{{ auth()->user()->role }}</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-xl text-sm font-bold text-red-500 hover:bg-red-50 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- 3. MOBILE OVERLAY --}}
        <div 
            x-show="mobileMenuOpen" 
            x-cloak
            @click="mobileMenuOpen = false" 
            class="fixed inset-0 bg-gray-900/50 z-40 md:hidden transition-opacity"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
        </div>

        {{-- 4. MAIN CONTENT AREA --}}
        <main class="flex-1 h-screen overflow-y-auto">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

    </div>

</body>
</html>