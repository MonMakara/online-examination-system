@extends('layouts.teacher')

@section('title', 'Student Grades')

@section('content')
<div class="space-y-6 px-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Gradebook</h2>
            <p class="text-sm text-gray-500">View and manage student performance across your exams.</p>
        </div>
        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Print Report
        </button>
    </div>

    @forelse($exams as $exam)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-8 py-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">{{ $exam->classRoom->name }}</span>
                    <h3 class="text-lg font-bold text-gray-800">{{ $exam->title }}</h3>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-500">Average Score</span>
                    <p class="text-lg font-black text-indigo-600">
                        {{ number_format($exam->results->avg('score') ?? 0, 1) }}%
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-white">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase">Student Name</th>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase">Date Taken</th>
                            <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase">Status</th>
                            <th class="px-8 py-4 text-right text-xs font-bold text-gray-400 uppercase">Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($exam->results as $result)
                            <tr class="hover:bg-gray-50/30 transition">
                                <td class="px-8 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-600 mr-3">
                                            {{ substr($result->student->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-bold text-gray-900">{{ $result->student->name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $result->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap">
                                    @php
                                        $pass = $result->score >= 50;
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $pass ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $pass ? 'Passed' : 'Failed' }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-black {{ $pass ? 'text-gray-900' : 'text-red-600' }}">
                                        {{ $result->score }} / 100
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-10 text-center text-gray-400 text-sm italic">
                                    No students have taken this exam yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl p-20 text-center border-2 border-dashed border-gray-200">
            <p class="text-gray-500 font-medium">No exams found. Create an exam to see grades.</p>
        </div>
    @endforelse
</div>
@endsection