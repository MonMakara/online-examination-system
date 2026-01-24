@extends('layouts.student')

@section('title', 'Assessments')

@section('content')
{{-- Initialize Alpine Data for Tabs --}}
<div class="container mx-auto px-4 lg:px-8 py-8" x-data="{ currentTab: 'active' }">
    
    {{-- Page Header --}}
    <div class="mb-6 border-b border-gray-200 pb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Assessments</h1>
            <p class="text-sm text-gray-500 mt-1">Manage your upcoming and past due assignments.</p>
        </div>

        {{-- Tab Navigation --}}
        <div class="bg-gray-100 p-1 rounded-lg inline-flex">
            <button 
                @click="currentTab = 'active'"
                :class="currentTab === 'active' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 text-sm font-bold rounded-md transition-all relative">
                Active Exams
                @if($activeExams->count() > 0)
                    <span class="ml-2 bg-red-600 text-white py-0.5 px-2 rounded-full text-xs shadow-sm">
                        {{ $activeExams->count() }}
                    </span>
                @endif
            </button>
            <button 
                @click="currentTab = 'missed'"
                :class="currentTab === 'missed' ? 'bg-white text-red-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                class="px-4 py-2 text-sm font-bold rounded-md transition-all relative">
                Missed / Expired
                @if($missedExams->count() > 0)
                    <span class="ml-2 bg-red-600 text-white py-0.5 px-2 rounded-full text-xs shadow-sm">
                        {{ $missedExams->count() }}
                    </span>
                @endif
            </button>
        </div>
    </div>

    {{-- TAB 1: ACTIVE EXAMS --}}
    <div x-show="currentTab === 'active'" x-transition.opacity>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($activeExams as $exam)
                @php
                    $isOverdue = $exam->due_at && now()->gt($exam->due_at);
                @endphp
                
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all flex flex-col overflow-hidden">
                    {{-- Header --}}
                    <div class="p-5 border-b border-gray-50 bg-gray-50/50">
                        <div class="flex justify-between items-start mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase tracking-wider">
                                {{ $exam->classRoom->name }}
                            </span>
                            @if($isOverdue)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 uppercase tracking-wider">Overdue</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-wider">Active</span>
                            @endif
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 leading-snug">{{ $exam->title }}</h3>
                    </div>

                    {{-- Body --}}
                    <div class="p-5 flex-1 space-y-4">
                        <div class="flex items-center justify-between text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="text-xs font-medium">{{ $exam->duration }} Mins</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                <span class="text-xs font-medium">{{ $exam->questions_count }} Qs</span>
                            </div>
                        </div>

                        <div class="bg-blue-50/30 rounded-lg p-3 space-y-2 border border-blue-50">
                            @if($exam->due_at)
                                <div class="flex items-center text-xs">
                                    <span class="w-16 text-gray-400 font-medium">Due:</span>
                                    <span class="{{ $isOverdue ? 'text-amber-600' : 'text-gray-700' }} font-semibold">{{ $exam->due_at->format('M d, h:i A') }}</span>
                                </div>
                            @endif
                            @if($exam->closed_at)
                                <div class="flex items-center text-xs">
                                    <span class="w-16 text-gray-400 font-medium">Closes:</span>
                                    <span class="text-red-600 font-semibold italic">{{ now()->diffForHumans($exam->closed_at, true) }} left</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="p-5 pt-0">
                        <a href="{{ route('student.exams.start', $exam->id) }}"
                           onclick="return confirm('Start exam? Timer begins immediately.')"
                           class="w-full flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-bold text-white shadow-sm transition-all
                           {{ $isOverdue ? 'bg-amber-600 hover:bg-amber-700' : 'bg-blue-600 hover:bg-blue-700' }}">
                            {{ $isOverdue ? 'Start Late Submission' : 'Begin Assessment' }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 flex flex-col items-center justify-center text-center">
                    <div class="bg-gray-50 p-4 rounded-full mb-3">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-gray-900 font-bold">All Caught Up!</h3>
                    <p class="text-gray-500 text-sm mt-1">You have no pending exams at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- TAB 2: MISSED EXAMS --}}
    <div x-show="currentTab === 'missed'" x-cloak style="display: none;">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($missedExams as $exam)
                {{-- Grayed out card style for missed items --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl flex flex-col overflow-hidden opacity-75 hover:opacity-100 transition-opacity">
                    
                    <div class="p-5 border-b border-gray-200 bg-gray-100">
                        <div class="flex justify-between items-start mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-gray-200 text-gray-600 border border-gray-300 uppercase tracking-wider">
                                {{ $exam->classRoom->name }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 border border-red-200 uppercase tracking-wider">
                                Missed
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-600 leading-snug line-through decoration-gray-400">{{ $exam->title }}</h3>
                    </div>

                    <div class="p-5 flex-1 space-y-4">
                        <div class="flex items-center justify-between text-gray-400">
                            <div class="flex items-center">
                                <span class="text-xs font-medium">{{ $exam->duration }} Mins</span>
                            </div>
                            <div class="flex items-center">
                                <span class="text-xs font-medium">{{ $exam->questions_count }} Qs</span>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-3 space-y-2 border border-gray-200">
                            <div class="flex items-center text-xs">
                                <span class="w-16 text-gray-400 font-medium">Closed:</span>
                                <span class="text-gray-600 font-semibold">
                                    {{ $exam->closed_at->format('M d, Y') }}
                                </span>
                            </div>
                            <p class="text-[10px] text-red-500 mt-1 italic">
                                The deadline for this exam has passed. You can no longer take it.
                            </p>
                        </div>
                    </div>

                    <div class="p-5 pt-0">
                        <button disabled class="w-full flex items-center justify-center px-4 py-2.5 rounded-lg text-sm font-bold bg-gray-200 text-gray-400 cursor-not-allowed">
                            Submission Closed
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 flex flex-col items-center justify-center text-center">
                    <div class="bg-green-50 p-4 rounded-full mb-3">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-gray-900 font-bold">Great Job!</h3>
                    <p class="text-gray-500 text-sm mt-1">You haven't missed any exams.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection