@extends('layouts.teacher')

@section('title', 'Exams & Quizzes')

@section('content')
    <div class="space-y-6 px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Manage Exams</h2>
                <p class="text-sm text-gray-500">Create, edit, and monitor your academic assessments.</p>
            </div>
            <a href="{{ route('teacher.exams.create') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Exam
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-xl border border-gray-200">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Exams</p>
                <p class="text-2xl font-black text-gray-800">{{ $exams->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-200">
                <p class="text-[10px] font-bold text-green-500 uppercase tracking-widest">Published</p>
                <p class="text-2xl font-black text-gray-800">{{ $exams->where('status', 'published')->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-200">
                <p class="text-[10px] font-bold text-yellow-500 uppercase tracking-widest">Drafts</p>
                <p class="text-2xl font-black text-gray-800">{{ $exams->where('status', 'draft')->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-200">
                <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">Questions Total</p>
                <p class="text-2xl font-black text-gray-800">{{ $exams->sum('questions_count') }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Exam
                                Info</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Classrooms</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Questions</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($exams as $exam)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $exam->title }}</div>
                                    <div class="text-xs text-gray-400">Created {{ $exam->created_at->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if ($exam->classRoom)
                                            <div class="h-7 w-7 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-[10px] font-bold text-indigo-700"
                                                title="{{ $exam->classRoom->name }}">
                                                {{ substr($exam->classRoom->name, 0, 1) }}
                                            </div>
                                            <span
                                                class="ml-2 text-sm text-gray-600 font-medium">{{ $exam->classRoom->name }}</span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Not assigned</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 text-xs font-bold">
                                        {{ $exam->questions_count }} Qs
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($exam->status === 'published')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span>
                                            Published
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-yellow-500"></span>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('teacher.exams.edit', $exam->id) }}"
                                            class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('teacher.exams.destroy', $exam->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this exam?')">
                                            @csrf @method('DELETE')
                                            <button
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
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
