@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
    <div class="space-y-4 mb-6 px-8">
        @if (session('success'))
            <div class="flex items-center p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm"
                role="alert">
                <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="flex items-center p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded-r-lg shadow-sm"
                role="alert">
                <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-bold">{{ session('info') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div class="flex items-center p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm"
                role="alert">
                <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-bold">{{ session('warning') }}</span>
            </div>
        @endif
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-indigo-600 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }} </h2>
                    <p class="text-indigo-100 mt-2">You have 3 pending exams this week. Good luck!</p>
                </div>
                <div class="absolute -right-10 -top-10 h-40 w-40 bg-white/10 rounded-full"></div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">My Classrooms</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($classes as $class)
                        <div class="p-4 border border-gray-100 rounded-xl hover:border-indigo-700 transition group">
                            <p class="font-bold text-gray-800">{{ $class->name }}</p>
                            <p class="text-xs text-gray-500">Teacher: {{ $class->teacher->name }}</p>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-6 border-2 border-dashed rounded-xl">
                            <p class="text-gray-400">You haven't joined any classes yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-4">Join New Class</h3>
                <form action="{{ route('student.join.class') }}" method="POST">
                    @csrf
                    <input type="text" name="code" placeholder="Enter 6-digit Code"
                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-700 outline-none text-center font-mono text-lg uppercase tracking-widest">
                    @error('code')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <button type="submit"
                        class="w-full mt-3 bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-sm">
                        Join Classroom
                    </button>
                </form>
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
