<aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col h-screen sticky top-0">
    <div class="p-6">
        <a href="{{ route('admin.dashboard') }}" class="text-2xl font-black text-indigo-600 tracking-tighter">
            Exam<span class="text-gray-900">System</span>
        </a>
    </div>

    <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.teachers.index') }}"
            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.teachers.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Teachers
        </a>

        <a href="{{ route('admin.classes.index') }}"
            class="flex items-center px-4 py-3 text-sm font-bold rounded-xl transition-all {{ request()->routeIs('admin.classes.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            Classrooms
        </a>
    </nav>

    {{-- Bottom User Profile Section --}}
    <div class="p-4 border-t border-gray-100">
        <div class="flex items-center p-2 mb-4 rounded-xl bg-gray-50 border border-gray-100">
            <div class="h-9 w-9 rounded-full bg-white border border-gray-200 flex items-center justify-center text-indigo-700 font-black mr-3 overflow-hidden shadow-sm shrink-0">
                @if (auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" class="h-full w-full object-cover">
                @else
                    {{ substr(auth()->user()->name, 0, 1) }}
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-black text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter truncate">ID: #{{ auth()->id() }}</p>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button onclick="return confirm('Are you sure you want to logout?')" type="submit"
                class="w-full flex items-center px-4 py-2.5 text-xs font-black uppercase tracking-widest text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>