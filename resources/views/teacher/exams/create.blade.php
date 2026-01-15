@extends('layouts.teacher')

@section('title', 'Create New Exam')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('teacher.exams.store') }}" method="POST">
        @csrf
        <div class="space-y-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-lg font-bold text-gray-800">Exam Details</h2>
                </div>
                
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Exam Title</label>
                        <input type="text" name="title" required placeholder="e.g. Mid-Term Mathematics"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Assign to Classroom</label>
                        <select name="class_id" required 
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition bg-white">
                            <option value="" disabled selected>Select a class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Duration (Minutes)</label>
                        <div class="relative">
                            <input type="number" name="duration" required min="1" placeholder="60"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            <span class="absolute right-4 top-3.5 text-sm text-gray-400">mins</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-8 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-white rounded-full mb-4 shadow-sm">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900">Next Step: Add Questions</h3>
                <p class="text-sm text-gray-600 mt-1 mb-6">Once you save these basic details, you will be able to add multiple-choice questions to your exam.</p>
                
                <div class="flex items-center justify-center space-x-4">
                    <a href="{{ route('teacher.exams.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700">Cancel</a>
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition transform hover:-translate-y-0.5">
                        Save & Continue
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection