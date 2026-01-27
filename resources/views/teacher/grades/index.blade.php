@extends('layouts.teacher')

@section('title', 'Student Grades')

@section('content')
    <div class="mb-6 px-4 lg:px-8">
        <a href="{{ route('teacher.grades.index') }}"
            class="inline-flex items-center text-xs font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 transition-colors group">
            <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            Back to Overview
        </a>
    </div>

    <div class="space-y-6 px-4 lg:px-8">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Gradebook</h2>
                <p class="text-sm text-gray-500 font-medium">View and manage student performance across your exams.</p>
            </div>

        </div>

        @forelse($exams as $exam)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
                {{-- Exam Header Card --}}
                <div class="px-8 py-5 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                    <div class="flex items-center">
                        {{-- Classroom Icon --}}
                        <div class="h-10 w-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center mr-4 shadow-sm">
                            <span class="text-indigo-600 font-black text-sm">{{ substr($exam->classRoom->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-indigo-500 uppercase tracking-[0.2em] block mb-0.5">{{ $exam->classRoom->name }}</span>
                            <h3 class="text-lg font-black text-gray-900 leading-tight">{{ $exam->title }}</h3>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Average Score</span>
                        <div class="inline-flex items-center px-3 py-1 bg-indigo-600 rounded-lg shadow-lg shadow-indigo-100">
                            <p class="text-lg font-black text-white">
                                {{ number_format($exam->results_avg_score ?? 0, 1) }}%
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-5 bg-white flex justify-between items-center group-hover:bg-gray-50 transition-colors">
                    <div class="flex items-center space-x-8 text-xs font-bold text-gray-500">
                        <div class="flex items-center">
                            <span class="text-indigo-500 mr-2">●</span>
                            {{ $exam->results_count }} Submissions
                        </div>
                        <div class="flex items-center">
                            <span class="text-emerald-500 mr-2">●</span>
                            {{ $exam->results_avg_score ? number_format($exam->results_avg_score, 1) : 0 }}% Avg Score
                        </div>
                    </div>
                    
                    <a href="{{ route('teacher.grades.exam', $exam->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-indigo-100 transition-colors">
                        View Results
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl p-20 text-center border-2 border-dashed border-gray-200">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-50 rounded-full mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-black text-gray-900 uppercase tracking-widest">No Grading Data</h3>
                <p class="text-gray-500 max-w-xs mx-auto mt-2 font-medium">Create and publish exams to start collecting student grades.</p>
            </div>
        @endforelse
    </div>
    <div class="px-4 lg:px-8 pb-8">
        {{ $exams->links() }}
    </div>
@endsection