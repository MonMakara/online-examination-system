@extends('layouts.student')

@section('title', $exam->title)

@section('content')
<div class="max-w-4xl mx-auto pb-20" x-data="examTimer({{ $exam->duration * 60 }})">
    <div class="sticky top-20 z-20 bg-white shadow-sm border border-indigo-100 rounded-2xl p-4 mb-8 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-indigo-50 rounded-xl">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Time Remaining</p>
                <p class="text-2xl font-black text-indigo-600" x-text="formatTime()"></p>
            </div>
        </div>
        <button type="button" @click="confirmSubmit()" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100">
            Submit Exam
        </button>
    </div>

    <form id="examForm" action="{{ route('student.exams.submit', $exam->id) }}" method="POST">
        @csrf
        <div class="space-y-8">
            @foreach($exam->questions as $index => $question)
                <div class="bg-white rounded-3xl border border-gray-200 p-8 shadow-sm">
                    <div class="flex items-start space-x-4 mb-6">
                        <span class="bg-gray-100 text-gray-700 w-8 h-8 rounded-lg flex items-center justify-center font-bold shrink-0">
                            {{ $index + 1 }}
                        </span>
                        <h3 class="text-lg font-bold text-gray-800">{{ $question->question }}</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ml-12">
                        @foreach(['a', 'b', 'c', 'd'] as $letter)
                            @php $opt = "option_" . $letter; @endphp
                            <label class="relative flex items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:bg-indigo-50 hover:border-indigo-200 transition group">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $letter }}" class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <span class="ml-4 text-gray-700 font-medium capitalize">
                                    <span class="text-gray-400 font-bold mr-1">{{ $letter }}.</span> {{ $question->$opt }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </form>
</div>

<script>
function examTimer(duration) {
    return {
        secondsLeft: duration,
        init() {
            let timer = setInterval(() => {
                this.secondsLeft--;
                if (this.secondsLeft <= 0) {
                    clearInterval(timer);
                    document.getElementById('examForm').submit();
                }
            }, 1000);
        },
        formatTime() {
            const mins = Math.floor(this.secondsLeft / 60);
            const secs = this.secondsLeft % 60;
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        },
        confirmSubmit() {
            if(confirm('Are you sure you want to end the exam now?')) {
                document.getElementById('examForm').submit();
            }
        }
    }
}
</script>
@endsection