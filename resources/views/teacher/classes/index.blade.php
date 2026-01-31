@extends('layouts.teacher')

@section('title', 'My Classrooms')

@section('content')
<div class="px-4 lg:px-8 space-y-6">
    {{-- Flash Messages --}}
    <div class="space-y-4">
        @if (session('success'))
            <div class="flex items-center p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm" role="alert">
                <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="flex items-center p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded-r-lg shadow-sm" role="alert">
                <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-bold">{{ session('info') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div class="flex items-center p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm" role="alert">
                <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-bold">{{ session('warning') }}</span>
            </div>
        @endif
    </div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    @forelse($classes as $class)
        <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="p-6">
                {{-- Class Header: Logo & Title --}}
                <div class="flex items-start justify-between mb-5">
                    <div class="h-14 w-14 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center overflow-hidden shadow-inner">
                        @if ($class->logo)
                            <img src="{{ $class->logo_url }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-indigo-600 font-black text-xl">{{ substr($class->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wide">
                        {{ $class->students_count ?? 0 }} Students
                    </span>
                </div>

                <h3 class="text-xl font-black text-gray-900 group-hover:text-indigo-600 transition-colors leading-tight mb-4">
                    {{ $class->name }}
                </h3>

                {{-- Join Code Box --}}
                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div>
                        <p class="text-[9px] uppercase font-black text-gray-400 tracking-widest mb-1">Access Join Code</p>
                        <p class="font-mono text-xl font-bold text-indigo-700 leading-tight tracking-wider">{{ $class->code }}</p>
                    </div>
                    <button onclick="copyToClipboard('{{ $class->code }}')" 
                        class="p-2.5 bg-white text-gray-500 hover:text-indigo-600 hover:shadow-sm border border-gray-200 rounded-lg transition-all active:scale-90"
                        title="Copy Code">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Footer Link --}}
            <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex justify-center">
                <a href="{{ route('teacher.classes.show', $class->id) }}" 
                   class="text-xs font-black uppercase tracking-widest text-gray-500 hover:text-indigo-600 transition flex items-center">
                    Manage Classroom 
                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-3xl border-2 border-dashed border-gray-200 p-16 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-50 rounded-full mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-black text-gray-900">No Classes Assigned</h3>
            <p class="text-gray-500 max-w-sm mx-auto mt-2 font-medium">You currently don't have any classrooms assigned to you. Contact your admin to set up your first class.</p>
        </div>
    @endforelse
</div>
</div>
<div class="mt-8">
    {{ $classes->links() }}
</div>

{{-- Subtle Toast Notification instead of Browser Alert --}}
<div id="copy-toast" class="fixed bottom-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-6 py-3 rounded-2xl text-sm font-bold opacity-0 transition-opacity duration-300 pointer-events-none z-50">
    Join code copied to clipboard!
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find all alert divs
        const alerts = document.querySelectorAll('[role="alert"]');

        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }, 3000); // 3 seconds
        });
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            const toast = document.getElementById('copy-toast');
            toast.classList.remove('opacity-0');
            setTimeout(() => toast.classList.add('opacity-0'), 2000);
        });
    }
</script>