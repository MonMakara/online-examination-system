<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 lg:translate-x-0 lg:static lg:flex lg:flex-col lg:h-screen lg:sticky lg:top-0">
    <div class="p-6">
        <span class="text-2xl font-bold text-indigo-600">
            <a href="{{ route('teacher.dashboard') }}">ExamSystem</a>
        </span>
        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mt-1">Teacher Portal</p>
    </div>

    <nav class="flex-1 px-4 space-y-2">
        <a href="{{ route('teacher.dashboard') }}" 
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('teacher.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }} transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>
        
        <a href="{{ route('teacher.classes.index') }}" 
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('teacher.classes.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }} transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            My Classrooms
        </a>

        <a href="{{ route('teacher.exams.index') }}" 
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('teacher.exams.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }} transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Exams & Quizzes
        </a>

        <a href="{{ route('teacher.grades.index') }}" 
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('teacher.grades.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600' }} transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
            </svg>
            Grade Reports
        </a>
    </nav>

    <div class="p-4 border-t border-gray-100">
        <a href="{{ route('teacher.profile') }}" class="flex items-center p-2 mb-4 rounded-lg hover:bg-gray-50 transition-colors">
            <div class="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold mr-3 overflow-hidden">
                @if(auth()->user()->profile_image)
                    <img src="{{ auth()->user()->profile_image_url }}" class="h-full w-full object-cover">
                @else
                    {{ substr(auth()->user()->name, 0, 1) }}
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">Teacher</p>
            </div>
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button onclick="return confirm('Are you sure to logout?')" type="submit" class="w-full flex items-center px-4 py-2 text-sm font-medium text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>