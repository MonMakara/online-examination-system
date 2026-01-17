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
            <button onclick="window.print()"
                class="inline-flex items-center px-5 py-2.5 bg-white border border-gray-200 rounded-xl font-black text-[11px] uppercase tracking-widest text-gray-600 hover:bg-gray-50 transition shadow-sm active:scale-95">
                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Report
            </button>
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
                                {{ number_format($exam->results->avg('score') ?? 0, 1) }}%
                            </p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead>
                            <tr class="bg-white">
                                <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Student Details</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Date Taken</th>
                                <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Final Score</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($exam->results as $result)
                                <tr class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-8 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full border-2 border-white shadow-sm flex-shrink-0 overflow-hidden bg-gray-100">
                                                @if ($result->student->profile_image)
                                                    <img src="{{ asset('storage/' . $result->student->profile_image) }}"
                                                        alt="{{ $result->student->name }}"
                                                        class="h-full w-full object-cover">
                                                @else
                                                    <div class="h-full w-full flex items-center justify-center text-[10px] font-black text-gray-400 uppercase">
                                                        {{ substr($result->student->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="ml-4">
                                                <span class="text-sm font-black text-gray-900 group-hover:text-indigo-600 transition">{{ $result->student->name }}</span>
                                                <span class="text-[10px] text-gray-400 font-bold block">{{ $result->student->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-4 whitespace-nowrap text-[11px] font-bold text-gray-500">
                                        {{ $result->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-8 py-4 whitespace-nowrap">
                                        @php $pass = $result->score >= 50; @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $pass ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                                            {{ $pass ? 'Passed' : 'Failed' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-black {{ $pass ? 'text-gray-900' : 'text-red-600' }}">
                                            {{ $result->score }}<span class="text-gray-300 mx-1">/</span>100
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-12 text-center">
                                        <p class="text-sm font-bold text-gray-400 italic">No submissions for this exam yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
@endsection