@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="space-y-6 px-4 lg:px-8">
    
    {{-- Standard Notification Bar --}}
    @if (session('success') || session('warning'))
        <div class="mb-6">
            @if (session('success'))
                <div class="flex items-center p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('warning'))
                <div class="flex items-center p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <span class="text-sm font-medium">{{ session('warning') }}</span>
                </div>
            @endif
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Overview and Classes --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Welcome Header --}}
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">Welcome, {{ Auth::user()->name }}</h2>
                        <p class="text-gray-500 mt-1">You have <span class="font-semibold text-indigo-600">{{ $pendingExamsCount }}</span> assessments waiting for you.</p>
                    </div>
                    <div class="hidden sm:block">
                        <a href="{{ route('student.exams.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm transition font-medium">
                            Go to Exams
                        </a>
                    </div>
                </div>
            </div>

            {{-- My Classes Grid --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">My Enrolled Classes</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($classes as $class)
                            <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition group">
                                <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $class->name }}</p>
                                    <p class="text-xs text-gray-500">Instructor: {{ $class->teacher->name }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-10 text-center border-2 border-dashed border-gray-200 rounded-lg">
                                <p class="text-gray-400 text-sm italic">You are not enrolled in any classes yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Sidebar Actions --}}
        <div class="space-y-6">
            
            {{-- Join Class Card --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Join a Classroom</h3>
                </div>
                <div class="p-6 text-center">
                    <p class="text-xs text-gray-500 mb-4">Enter the 6-character code provided by your teacher to enroll.</p>
                    <form action="{{ route('student.join.class') }}" method="POST">
                        @csrf
                        <input type="text" name="code" placeholder="CODE01" maxlength="6"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-center font-mono text-xl uppercase tracking-widest mb-3 transition">
                        @error('code')
                            <p class="text-xs text-red-600 mb-3">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white py-2.5 rounded-lg text-sm font-bold transition">
                            Enroll Now
                        </button>
                    </form>
                </div>
            </div>

            {{-- Helpful Info --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5">
                <h4 class="text-xs font-bold text-indigo-800 uppercase tracking-widest mb-2">Notice</h4>
                <p class="text-xs text-indigo-700 leading-relaxed">
                    Exams may have a <span class="font-bold">Due Date</span> (soft deadline) and a <span class="font-bold">Closed Date</span> (hard deadline). Always check the exam details before starting.
                </p>
            </div>

        </div>
    </div>
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
</script>