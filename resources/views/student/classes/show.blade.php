@extends('layouts.student')

@section('title', $class->name)

@section('content')
<div class="px-4 lg:px-8 py-8 space-y-6">
    
    {{-- Back Button --}}
    <div>
        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center text-gray-500 hover:text-indigo-600 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span class="font-medium">Back to Dashboard</span>
        </a>
    </div>

    {{-- Class Header Card --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 md:p-8">
        <div class="flex flex-col md:flex-row items-center md:items-start text-center md:text-left">
            {{-- Logo --}}
            <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-8">
                <div class="w-32 h-32 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden shadow-sm">
                    @if($class->logo)
                        <img src="{{ asset('storage/' . $class->logo) }}" alt="{{ $class->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-5xl font-bold text-gray-300">{{ substr($class->name, 0, 1) }}</span>
                    @endif
                </div>
            </div>

            {{-- Info --}}
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $class->name }}</h1>
                <div class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-bold border border-indigo-100 mb-6">
                    Class Code: <span class="font-mono ml-2">{{ $class->code }}</span>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start space-y-4 sm:space-y-0 sm:space-x-8">
                    {{-- Teacher Info --}}
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gray-100 border border-white shadow-sm overflow-hidden mr-3">
                             @if($class->teacher && $class->teacher->profile_image)
                                <img src="{{ asset('storage/' . $class->teacher->profile_image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-indigo-50 text-indigo-600 font-bold">
                                    {{ substr($class->teacher->name ?? '?', 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Instructor</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $class->teacher->name ?? 'Unassigned' }}</p>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mr-3">
                           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <div class="text-left">
                            <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold">Exams</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $class->exams->count() }} Posted</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Exams List --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Class Exams</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($class->exams as $exam)
                <div class="p-6 hover:bg-gray-50 transition flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div class="mb-4 md:mb-0">
                        <h4 class="text-base font-bold text-gray-900 mb-1">{{ $exam->title }}</h4>
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                             <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $exam->duration }} mins</span>
                            </div>
                            @if($exam->due_at)
                                <div class="flex items-center text-amber-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Due: {{ \Carbon\Carbon::parse($exam->due_at)->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div>
                        {{-- Determine status --}}
                        @php
                            $now = now();
                            $isClosed = $exam->closed_at && $now->gt($exam->closed_at);
                            // We can't easily check 'taken' here without passing user results, so we'll link to Start/View
                        @endphp

                        @if($isClosed)
                             <span class="inline-flex px-3 py-1 bg-gray-100 text-gray-500 rounded-lg text-sm font-medium">Closed</span>
                        @else
                            <a href="{{ route('student.exams.start', $exam->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition shadow-sm shadow-indigo-200">
                                View Exam
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-gray-500 font-medium">No exams have been posted for this class yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
