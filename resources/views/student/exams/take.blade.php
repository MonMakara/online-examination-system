@extends('layouts.student')

@section('title', $exam->title)

@section('content')
<div class="container mx-auto px-6 py-8 pb-24" x-data="examTimer({{ $exam->duration * 60 }}, '{{ $exam->closed_at }}')">
    {{-- Sticky Header --}}
    <div class="sticky top-20 z-20 bg-white shadow-md border border-gray-200 rounded-xl p-5 mb-8 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <div class="p-2.5 bg-blue-50 rounded-lg text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Time Remaining</p>
                <p class="text-2xl font-bold text-blue-600 leading-none" x-text="formatTime()"></p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <div class="text-right hidden sm:block mr-2">
                <p class="text-xs font-bold text-gray-800">{{ $exam->title }}</p>
                <p class="text-[10px] text-gray-400 uppercase tracking-tighter">Total Questions: {{ $exam->questions->count() }}</p>
            </div>
            <button type="button" @click="confirmSubmit()" 
                class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-blue-700 transition shadow-sm">
                Submit Assessment
            </button>
        </div>
    </div>

    {{-- Question List --}}
    <form id="examForm" action="{{ route('student.exams.submit', $exam->id) }}" method="POST">
        @csrf
        <div class="space-y-6">
            @foreach($exam->questions as $index => $question)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    {{-- Question Header --}}
                    <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-start space-x-3">
                        <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white text-xs font-bold rounded flex items-center justify-center mt-0.5">
                            {{ $index + 1 }}
                        </span>
                        <h3 class="text-base font-bold text-gray-800 leading-relaxed">
                            {{ $question->question }}
                        </h3>
                    </div>

                    {{-- Options Grid --}}
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach(['a', 'b', 'c', 'd'] as $letter)
                                @php $opt = "option_" . $letter; @endphp
                                <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50/50 hover:border-blue-200 transition group">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $letter }}" 
                                        class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                    <div class="ml-4">
                                        <span class="text-xs font-bold text-gray-400 uppercase mr-1">{{ $letter }}.</span>
                                        <span class="text-sm font-medium text-gray-700">{{ $question->$opt }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </form>
</div>



<script>
function examTimer(durationInSeconds, closedAtString) {
    return {
        secondsLeft: durationInSeconds,
        hardDeadline: closedAtString ? new Date(closedAtString).getTime() : null,
        
        init() {
            let timer = setInterval(() => {
                // 1. Standard duration countdown
                this.secondsLeft--;

                // 2. Hard Deadline Check (If closed_at is reached before duration ends)
                if (this.hardDeadline) {
                    const now = new Date().getTime();
                    const diffToHardClose = Math.floor((this.hardDeadline - now) / 1000);
                    
                    // If hard close is sooner than duration, follow hard close
                    if (diffToHardClose < this.secondsLeft) {
                        this.secondsLeft = diffToHardClose;
                    }
                }

                // 3. Auto-Submit
                if (this.secondsLeft <= 0) {
                    this.secondsLeft = 0;
                    clearInterval(timer);
                    alert('Time has expired! Your exam will be submitted automatically.');
                    document.getElementById('examForm').submit();
                }
            }, 1000);
        },

        formatTime() {
            if (this.secondsLeft <= 0) return "00:00";
            const mins = Math.floor(this.secondsLeft / 60);
            const secs = this.secondsLeft % 60;
            return `${mins}:${secs.toString().padStart(2, '0')}`;
        },

        confirmSubmit() {
            if(confirm('Are you sure you want to submit your answers? You cannot change them after this.')) {
                document.getElementById('examForm').submit();
            }
        }
    }
}
</script>
@endsection