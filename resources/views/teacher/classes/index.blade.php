@extends('layouts.teacher')

@section('title', 'My Classrooms')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-8">
    @forelse($classes as $class)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
            <div class="p-6 border-b border-gray-50">
                <h3 class="text-xl font-bold text-gray-900">{{ $class->name }}</h3>
                <div class="mt-4 flex items-center justify-between bg-indigo-50 p-3 rounded-lg border border-indigo-100">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-indigo-400">Join Code</p>
                        <p class="font-mono text-lg font-bold text-indigo-700 leading-tight">{{ $class->code }}</p>
                    </div>
                    <button onclick="copyToClipboard('{{ $class->code }}')" class="p-2 text-indigo-600 hover:bg-indigo-100 rounded-md transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                    </button>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-between items-center text-sm">
                <span class="text-gray-500 font-medium">{{ $class->students_count ?? 0 }} Students</span>
                <a href="{{ route('teacher.classes.show', $class->id) }}" class="text-indigo-600 font-bold hover:text-indigo-800">Manage Class &rarr;</a>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-2xl border-2 border-dashed border-gray-200 p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">No Classes Assigned</h3>
            <p class="text-gray-500 max-w-xs mx-auto mt-2">You currently don't have any classrooms assigned to you. Please contact the administrator to get started.</p>
        </div>
    @endforelse
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        // Using a more subtle alert or you could use a toast notification
        alert('Join code copied to clipboard!');
    }
</script>
@endsection