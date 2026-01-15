@extends('layouts.teacher')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Edit Exam Settings</h2>
            
            <form action="{{ route('teacher.exams.update', $exam->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Exam Title</label>
                        <input type="text" name="title" value="{{ $exam->title }}" required 
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Classroom</label>
                            <select name="class_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none">
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ $exam->class_id == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Duration (Mins)</label>
                            <input type="number" name="duration" value="{{ $exam->duration }}" required 
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                        <a href="{{ route('teacher.exams.questions.create', $exam->id) }}" class="text-indigo-600 font-bold hover:underline text-sm">
                            Manage Questions instead?
                        </a>
                        <div class="flex space-x-3">
                            <a href="{{ route('teacher.exams.index') }}" class="px-6 py-3 text-gray-500 font-bold">Cancel</a>
                            <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition">
                                Update Exam
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection