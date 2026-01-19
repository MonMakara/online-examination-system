@extends('layouts.teacher')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 py-8">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Question</h1>
            <p class="text-gray-500">Exam: <span class="font-semibold text-indigo-600">{{ $exam->title }}</span></p>
        </div>
        <a href="{{ route('teacher.exams.questions.create', $exam->id) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-bold hover:bg-gray-200 transition">
            Cancel & Go Back
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <form action="{{ route('teacher.questions.update', $question->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Question Text</label>
                    <textarea name="question" rows="2" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">{{ old('question', $question->question) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach(['a', 'b', 'c', 'd'] as $opt)
                        @php $dbField = 'option_'.$opt; @endphp
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Option {{ strtoupper($opt) }}</label>
                            <input type="text" name="option_{{ $opt }}" value="{{ old('option_'.$opt, $question->$dbField) }}" required class="w-full mt-1 px-4 py-2.5 rounded-lg border border-gray-200 outline-none focus:border-indigo-500">
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between gap-6 pt-4 border-t border-gray-50">
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-indigo-700 mb-2">Correct Answer</label>
                        <select name="correct_option" required class="w-full px-4 py-2.5 rounded-lg border-2 border-indigo-100 outline-none focus:border-indigo-500">
                            @foreach(['a', 'b', 'c', 'd'] as $opt)
                                <option value="{{ $opt }}" {{ old('correct_option', $question->correct_option) == $opt ? 'selected' : '' }}>
                                    Option {{ strtoupper($opt) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="mt-6 px-10 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                        Update Question
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
