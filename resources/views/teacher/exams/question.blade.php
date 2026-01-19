@extends('layouts.teacher')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 py-8">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add Questions</h1>
            <p class="text-gray-500">Exam: <span class="font-semibold text-indigo-600">{{ $exam->title }}</span></p>
        </div>
        <a href="{{ route('teacher.exams.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-bold hover:bg-gray-200 transition">
            Finish & Exit
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <form action="{{ route('teacher.exam.questions.store', $exam->id) }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Question Text</label>
                    <textarea name="question" rows="2" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach(['a', 'b', 'c', 'd'] as $opt)
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Option {{ strtoupper($opt) }}</label>
                        <input type="text" name="option_{{ $opt }}" required class="w-full mt-1 px-4 py-2.5 rounded-lg border border-gray-200 outline-none focus:border-indigo-500">
                    </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between gap-6 pt-4 border-t border-gray-50">
                    <div class="w-1/2">
                        <label class="block text-sm font-bold text-indigo-700 mb-2">Correct Answer</label>
                        <select name="correct_option" required class="w-full px-4 py-2.5 rounded-lg border-2 border-indigo-100 outline-none focus:border-indigo-500">
                            <option value="a">Option A</option>
                            <option value="b">Option B</option>
                            <option value="c">Option C</option>
                            <option value="d">Option D</option>
                        </select>
                    </div>
                    <button type="submit" class="mt-6 px-10 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                        Save Question
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="space-y-4">
        <h3 class="text-lg font-bold text-gray-800 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            Exam Preview ({{ $exam->questions->count() }} Questions)
        </h3>
        
        @foreach($exam->questions as $index => $q)
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 relative group">
            <div class="absolute top-4 right-4 flex items-center space-x-2">
                <a href="{{ route('teacher.questions.edit', $q->id) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-2 py-1 rounded transition">Edit</a>
                <form action="{{ route('teacher.questions.destroy', $q->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this question?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 bg-red-50 px-2 py-1 rounded transition">Delete</button>
                </form>
                <span class="text-xs font-bold text-gray-300 uppercase ml-2">Q{{ $index + 1 }}</span>
            </div>
            <p class="font-bold text-gray-800 mb-3">{{ $q->question }}</p>
            <div class="grid grid-cols-2 gap-2">
                @foreach(['a', 'b', 'c', 'd'] as $opt)
                    @php $col = 'option_'.$opt; @endphp
                    <div class="text-sm {{ $q->correct_option == $opt ? 'text-green-600 font-bold' : 'text-gray-500' }}">
                        {{ strtoupper($opt) }}: {{ $q->$col }}
                        @if($q->correct_option == $opt) ✓ @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection