@extends('layouts.student')

@section('title', 'My Exam Results')

@section('content')
    <div class="container mx-auto px-4 lg:px-8 py-8">
        <div class="mb-8 border-b border-gray-200 pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Performance History</h1>
            <p class="text-sm text-gray-500 mt-1">Review your scores and submission details from completed assessments.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Exam
                                & Class</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Submission Info</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Deadline Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Grade Result</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Final Score</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($results as $result)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                {{-- Exam & Class --}}
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $result->exam->title }}</div>
                                    <div class="text-xs text-indigo-600 font-medium mt-0.5">
                                        {{ $result->exam->classRoom->name }}</div>
                                </td>

                                {{-- Submission Info & Teacher Fix --}}
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-xs text-gray-600">
                                        <span class="text-gray-400">Teacher:</span>
                                        {{ $result->exam->classRoom->teacher->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        Completed: {{ $result->created_at->format('M d, Y') }}
                                    </div>
                                </td>

                                {{-- Deadline Logic (Calculated on-the-fly) --}}
                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    @if ($result->exam->due_at && $result->created_at->gt($result->exam->due_at))
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100 uppercase tracking-tighter">
                                            Late Submission
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-tighter">
                                            On Time
                                        </span>
                                    @endif
                                </td>

                                {{-- Grade Result --}}
                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    @if ($result->score >= 50)
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-green-50 text-green-700 uppercase border border-green-200">
                                            Passed
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-50 text-red-700 uppercase border border-red-200">
                                            Failed
                                        </span>
                                    @endif
                                </td>

                                {{-- Final Score --}}
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <span
                                        class="text-xl font-bold {{ $result->score >= 50 ? 'text-gray-900' : 'text-red-600' }}">
                                        {{ $result->score }}<span class="text-sm font-normal text-gray-400">%</span>
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-center">
                                    <a href="{{ route('student.exams.review', $result->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-colors"
                                        title="Review Answers">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div
                                        class="inline-flex items-center justify-center w-12 h-12 bg-gray-50 rounded-lg mb-4">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-400 font-medium">You haven't completed any assessments yet.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>
        </div>
        
        <div class="mt-8">
            {{ $results->links() }}
        </div>
    </div>
@endsection
