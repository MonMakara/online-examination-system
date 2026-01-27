@extends('layouts.teacher')

@section('title', 'Exam Performance')

@section('content')
    <div class="mb-6 px-4 lg:px-8 flex justify-between items-center">
        <a href="{{ route('teacher.grades.class', $exam->class_id) }}"
            class="inline-flex items-center text-xs font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 transition-colors group">
            <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            Back to Exams
        </a>

        <button onclick="window.print()"
            class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg font-black text-[10px] uppercase tracking-widest text-gray-600 hover:bg-gray-50 transition shadow-sm active:scale-95">
            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Report
        </button>
    </div>

    <div class="space-y-6 px-4 lg:px-8">
        {{-- Exam Header Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-100 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div class="flex items-center">
                    {{-- Classroom Icon --}}
                    <div class="h-12 w-12 rounded-xl bg-white border border-gray-200 flex items-center justify-center mr-4 shadow-sm">
                        <span class="text-indigo-600 font-black text-lg">{{ substr($exam->classRoom->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] font-black text-indigo-500 uppercase tracking-[0.2em] block mb-0.5">{{ $exam->classRoom->name }}</span>
                        <h1 class="text-2xl font-black text-gray-900 leading-tight">{{ $exam->title }}</h1>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Submissions</span>
                        <p class="text-xl font-black text-gray-900">{{ $exam->results->count() }}</p>
                    </div>
                    <div class="w-px h-8 bg-gray-200 mx-2"></div>
                    <div class="text-right">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-1">Avg Score</span>
                        <div class="inline-flex items-center px-3 py-1 bg-indigo-600 rounded-lg shadow-lg shadow-indigo-100">
                            <p class="text-lg font-black text-white">
                                {{ number_format($exam->results->avg('score') ?? 0, 1) }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Student Results Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
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
                                                <img src="{{ $result->student->profile_image_url }}"
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
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-50 p-3 rounded-full mb-3">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        </div>
                                        <p class="text-sm font-bold text-gray-900">No Student Results</p>
                                        <p class="text-xs text-gray-500 mt-1">Students haven't taken this exam yet.</p>
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
