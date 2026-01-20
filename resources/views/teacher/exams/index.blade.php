@extends('layouts.teacher')

@section('title', 'Exams & Quizzes')

@section('content')
    <div class="space-y-6 px-4 lg:px-8 pb-8">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Exam Management</h2>
                <p class="text-sm text-gray-500 font-medium">Create, schedule, and monitor student assessments.</p>
            </div>
            <a href="{{ route('teacher.exams.create') }}"
                class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Exam
            </a>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $stats = [
                    [
                        'label' => 'Total Exams',
                        'value' => $exams->count(),
                        'color' => 'text-gray-800',
                        'bg' => 'bg-white',
                    ],
                    [
                        'label' => 'Published',
                        'value' => $exams->where('status', 'published')->count(),
                        'color' => 'text-emerald-600',
                        'bg' => 'bg-emerald-50/30',
                    ],
                    [
                        'label' => 'Drafts',
                        'value' => $exams->where('status', 'draft')->count(),
                        'color' => 'text-amber-600',
                        'bg' => 'bg-amber-50/30',
                    ],
                    [
                        'label' => 'Questions',
                        'value' => $exams->sum('questions_count'),
                        'color' => 'text-indigo-600',
                        'bg' => 'bg-indigo-50/30',
                    ],
                ];
            @endphp
            @foreach ($stats as $stat)
                <div class="{{ $stat['bg'] }} p-5 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] font-black {{ $stat['color'] }} uppercase tracking-widest opacity-70">
                        {{ $stat['label'] }}</p>
                    <p class="text-3xl font-black {{ $stat['color'] }} mt-1">{{ $stat['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- Main Table Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Exam Details</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Classroom</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Schedule</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Status</th>
                            <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($exams as $exam)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $exam->title }}</span>
                                        <div class="flex items-center mt-1 space-x-2">
                                            <span
                                                class="text-[10px] px-1.5 py-0.5 bg-indigo-50 text-indigo-600 rounded font-black uppercase border border-indigo-100">{{ $exam->questions_count }}
                                                Qs</span>
                                            <span class="text-[10px] text-gray-400 font-bold tracking-tight">ID:
                                                #{{ $exam->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    @if ($exam->classRoom)
                                        <div class="flex items-center">
                                            {{-- Fixed Classroom Logo Logic --}}
                                            <div
                                                class="h-9 w-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-xs font-bold text-indigo-700 overflow-hidden shrink-0 shadow-sm">
                                                @if ($exam->classRoom->logo)
                                                    <img src="{{ $exam->classRoom->logo_url }}"
                                                        class="h-full w-full object-cover">
                                                @else
                                                    <span
                                                        class="text-indigo-600 font-black text-sm">{{ substr($exam->classRoom->name, 0, 1) }}</span>
                                                @endif
                                            </div>
                                            <span
                                                class="ml-3 text-sm text-gray-700 font-bold group-hover:text-gray-900">{{ $exam->classRoom->name }}</span>
                                        </div>
                                    @else
                                        <span
                                            class="text-xs text-gray-400 font-bold italic uppercase tracking-wider">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        <div class="flex items-center text-[11px] text-gray-600">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-indigo-500" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="font-bold">Due:</span>
                                            <span
                                                class="ml-1 font-medium">{{ $exam->due_at ? $exam->due_at->format('M d, H:i') : 'No Limit' }}</span>
                                        </div>
                                        @if ($exam->closed_at && now()->gt($exam->closed_at))
                                            <span
                                                class="text-[9px] font-black text-red-500 uppercase flex items-center tracking-widest">
                                                <div class="w-1 h-1 rounded-full bg-red-500 mr-1.5 animate-pulse"></div>
                                                Expired
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    @if ($exam->status === 'published')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black bg-emerald-50 text-emerald-700 uppercase tracking-widest border border-emerald-100">Live</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black bg-amber-50 text-amber-700 uppercase tracking-widest border border-amber-100">Draft</span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-right text-sm font-medium">
                                    {{-- Fixed: Action Buttons on the same line --}}
                                    <div class="flex justify-end items-center space-x-2">
                                        <a href="{{ route('teacher.exams.edit', $exam->id) }}"
                                            class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition bg-white border border-gray-100 shadow-sm"
                                            title="Edit Exam">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('teacher.exams.destroy', $exam->id) }}" method="POST"
                                            class="flex items-center m-0" onsubmit="return confirm('Archive this exam?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition bg-white border border-gray-100 shadow-sm"
                                                title="Delete Exam">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-200 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-gray-500 font-medium">No exams created yet.</p>
                                        <a href="{{ route('teacher.exams.create') }}"
                                            class="mt-2 text-indigo-600 text-sm font-bold hover:underline">Create your first
                                            exam &rarr;</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
