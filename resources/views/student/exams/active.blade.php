@extends('layouts.student')

@section('title', 'Active Exams')

@section('content')
    <div class="px-8 py-6">
        <div class="mb-8">
            <h1 class="text-2xl font-black text-gray-900">Available Assessments</h1>
            <p class="text-gray-500 text-sm">Select an exam to start. Please ensure you have a stable connection.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($exams as $exam)
                <div
                    class="bg-white  rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span
                                class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-widest rounded-full">
                                {{ $exam->classRoom->name }}
                            </span>
                            <div class="flex items-center text-gray-400 text-xs font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $exam->duration }} Mins
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-indigo-600 transition">
                            {{ $exam->title }}
                        </h3>
                        <p class="text-xs text-gray-400 mb-6">
                            Instructor: {{ $exam->teacher?->name ?? 'Staff' }}
                        </p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-700">{{ $exam->questions_count }}</span>
                                <span class="text-[10px] text-gray-400 uppercase">Questions</span>
                            </div>

                            <a href="{{ route('student.exams.start', $exam->id) }}"
                                onclick="return confirm('Do you want to start this exam? The timer will begin immediately.')"
                                class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition transform hover:-translate-y-0.5">
                                Start Exam
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full py-20 bg-white rounded-3xl border-2 border-dashed border-gray-100 flex flex-col items-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-400 font-medium">No active exams at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
