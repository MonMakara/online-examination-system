<aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col h-screen sticky top-0">
    <div class="p-6">
        <span class="text-2xl font-bold text-emerald-600">
            <a href="">ExamSystem</a>
        </span>
        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mt-1">Student Portal</p>
    </div>

    <nav class="flex-1 px-4 space-y-2">
        <a href="" 
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()? 'bg-emerald-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }} transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            My Dashboard
        </a>
        
        <a href="" 
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()? 'bg-emerald-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }} transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            Active Exams
        </a>

        <a href="" 
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()? 'bg-emerald-600 text-white shadow-md' : 'text-gray-600 hover:bg-gray-50 hover:text-emerald-600' }} transition-all">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            My Results
        </a>
    </nav>

    <div class="px-4 mb-4">
        <div class="bg-emerald-50 rounded-xl p-4 border border-emerald-100">
            <p class="text-xs font-bold text-emerald-700 mb-2 uppercase">New Classroom?</p>
            <a href="{" class="block w-full text-center bg-emerald-600 text-white text-xs py-2 rounded-lg font-bold hover:bg-emerald-700 transition">
                Enter Code
            </a>
        </div>
    </div>

    <div class="p-4 border-t border-gray-100 bg-gray-50/50">
        <div class="flex items-center p-2 mb-4">
            <div class="h-9 w-9 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold mr-3 overflow-hidden">
                @if(auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" class="h-full w-full object-cover">
                @else
                    {{ substr(auth()->user()->name, 0, 1) }}
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-gray-500 truncate uppercase">ID: #{{ auth()->id() }}</p>
            </div>
        </div>

        <form action="{OST">
            @csrf
            <button type="submit" class="w-full flex items-center px-4 py-2 text-sm font-medium text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Sign Out
            </button>
        </form>
    </div>
</aside>