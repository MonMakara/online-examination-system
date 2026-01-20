@extends('layouts.teacher')

@section('title', 'Create New Exam')

@section('content')
<div class="max-w-4xl mx-auto px-4 lg:px-8 pb-12">
    <div class="flex items-center justify-between mb-6">
            <a href="{{ route('teacher.exams.index') }}"
                class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Back to Exams
            </a>
            <div class="flex space-x-3">
                <span
                    class="px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold border border-indigo-100">
                    Create New Exam
                </span>
            </div>
        </div>
    <form action="{{ route('teacher.exams.store') }}" method="POST">
        @csrf
        <div class="space-y-6">
            
            {{-- Exam Details Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-lg font-black text-gray-900 tracking-tight">Exam Details</h2>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Exam Title</label>
                        <input type="text" name="title" required placeholder="e.g. Mid-Term Mathematics"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium">
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Assign to Classroom</label>
                        <select name="class_id" required 
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition bg-white font-medium">
                            <option value="" disabled selected>Select a class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Duration</label>
                        <div class="relative">
                            <input type="number" name="duration" required min="1" placeholder="60"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium">
                            <span class="absolute right-4 top-3.5 text-[10px] font-black text-gray-400 uppercase">mins</span>
                        </div>
                    </div>

                    {{-- NEW: Due Date --}}
                    <div>
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Due Date (Deadline)</label>
                        <input type="datetime-local" name="due_at" 
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium text-gray-600">
                        <p class="mt-2 text-[10px] text-gray-400 font-bold italic">The recommended time for students to finish.</p>
                    </div>

                    {{-- NEW: Closed Date --}}
                    <div>
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Closed Date (Hard Cutoff)</label>
                        <input type="datetime-local" name="closed_at" 
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition font-medium text-gray-600">
                        <p class="mt-2 text-[10px] text-gray-400 font-bold italic">After this time, no one can start or submit.</p>
                    </div>
                </div>
            </div>

            {{-- Footer / CTA --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-8 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-white rounded-full mb-4 shadow-sm">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="font-black text-gray-900 uppercase tracking-tight">Next Step: Add Questions</h3>
                <p class="text-sm text-gray-500 mt-1 mb-8 font-medium">Save these details to proceed to the question builder.</p>
                
                <div class="flex items-center justify-center space-x-6">
                    <a href="{{ route('teacher.exams.index') }}" class="text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition">Cancel</a>
                    <button type="submit" class="bg-indigo-600 text-white px-10 py-4 rounded-xl font-black text-xs uppercase tracking-widest shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition transform hover:-translate-y-1 active:scale-95">
                        Save & Continue
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection