@extends('layouts.student')

@section('title', 'Active Exams')

@section('content')
<div class="container mx-auto px-6 py-8">
    {{-- Page Header --}}
    <div class="mb-8 border-b border-gray-200 pb-6">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Active Assessments</h1>
        <p class="text-sm text-gray-500 mt-1">Review and complete your assigned exams before the closing deadlines.</p>
    </div>

    {{-- Exam Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($exams as $exam)
            @php
                $isOverdue = $exam->due_at && now()->gt($exam->due_at);
            @endphp
            
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all flex flex-col overflow-hidden">
                
                {{-- Card Header --}}
                <div class="p-5 border-b border-gray-50 bg-gray-50/50">
                    <div class="flex justify-between items-start mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-wider">
                            {{ $exam->classRoom->name }}
                        </span>
                        
                        @if($isOverdue)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 uppercase tracking-wider">
                                Overdue
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wider">
                                Active
                            </span>
                        @endif
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 leading-snug">{{ $exam->title }}</h3>
                </div>

                {{-- Card Body --}}
                <div class="p-5 flex-1 space-y-4">
                    {{-- Quick Stats --}}
                    <div class="flex items-center justify-between text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-xs font-medium">{{ $exam->duration }} Mins</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="text-xs font-medium">{{ $exam->questions_count }} Qs</span>
                        </div>
                    </div>

                    {{-- Timeline Details --}}
                    <div class="bg-blue-50/30 rounded-lg p-3 space-y-2 border border-blue-50">
                        <div class="flex items-center text-xs">
                            <span class="w-16 text-gray-400 font-medium">Teacher:</span>
                            <span class="text-gray-700 font-semibold truncate">{{ $exam->classRoom->teacher->name ?? 'Staff' }}</span>
                        </div>
                        
                        @if($exam->due_at)
                            <div class="flex items-center text-xs">
                                <span class="w-16 text-gray-400 font-medium">Due Date:</span>
                                <span class="{{ $isOverdue ? 'text-amber-600' : 'text-gray-700' }} font-semibold">
                                    {{ $exam->due_at->format('M d, h:i A') }}
                                </span>
                            </div>
                        @endif

                        @if($exam->closed_at)
                            <div class="flex items-center text-xs">
                                <span class="w-16 text-gray-400 font-medium">Closes:</span>
                                <span class="text-red-600 font-semibold italic">
                                    {{ now()->diffForHumans($exam->closed_at, true) }} left
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Action Area --}}
                <div class="p-5 pt-0">
                    <a href="{{ route('student.exams.start', $exam->id) }}"
                       onclick="return confirm('Do you want to start this exam? The timer will begin immediately.')"
                       class="w-full flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-bold text-white shadow-sm transition-all
                       {{ $isOverdue ? 'bg-amber-600 hover:bg-amber-700 shadow-amber-100' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-100' }}">
                        
                        @if($isOverdue)
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Start Late Submission
                        @else
                            Begin Assessment
                        @endif
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white rounded-xl border border-gray-200 flex flex-col items-center">
                <div class="p-4 bg-gray-50 rounded-full mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">No Active Exams</h3>
                <p class="text-xs text-gray-400 mt-1">Check back later for new assignments from your instructor.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection