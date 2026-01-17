@extends('layouts.student')

@section('title', $exam->title)

@section('content')
{{-- Added exam ID and the formatted closedAt string to the x-data --}}
<div class="container mx-auto px-4 lg:px-8 py-8 pb-24" 
     x-data="examTimer({{ $exam->duration * 60 }}, '{{ $examClosedAt }}', '{{ $examDueAt }}', {{ $exam->id }})">
    
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
                {{-- Updated to use text property --}}
                <p class="text-2xl font-bold text-blue-600 leading-none" x-text="timerDisplay"></p>
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
function examTimer(durationInSeconds, closedAtString, dueAtString, examId) {
    return {
        timerDisplay: '00:00',
        endTime: null,
        interval: null,

        init() {
            const storageKey = 'exam_end_' + examId;
            const now = Date.now();

            // Base duration
            let calculatedEndTime = now + (durationInSeconds * 1000);

            // Hard close (absolute stop)
            if (closedAtString) {
                const closedTime = Date.parse(closedAtString);
                if (!isNaN(closedTime)) {
                    calculatedEndTime = Math.min(calculatedEndTime, closedTime);
                }
            }

            // Due date (soft stop)
            if (dueAtString) {
                const dueTime = Date.parse(dueAtString);
                if (!isNaN(dueTime)) {
                    calculatedEndTime = Math.min(calculatedEndTime, dueTime);
                }
            }

            // localStorage safety
            const savedEndTime = parseInt(localStorage.getItem(storageKey));
            if (savedEndTime && savedEndTime < calculatedEndTime) {
                this.endTime = savedEndTime;
            } else {
                this.endTime = calculatedEndTime;
                localStorage.setItem(storageKey, this.endTime);
            }

            this.tick();
            this.interval = setInterval(() => this.tick(), 1000);
        },

        tick() {
            const distance = this.endTime - Date.now();

            if (distance <= 0) {
                this.timerDisplay = "00:00";
                clearInterval(this.interval);

                const form = document.getElementById('examForm');
                if (form && form.dataset.submitting !== 'true') {
                    alert('Time is up! Exam submitted automatically.');
                    this.submitForm();
                }
                return;
            }

            const minutes = Math.floor(distance / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            this.timerDisplay =
                minutes.toString().padStart(2, '0') + ':' +
                seconds.toString().padStart(2, '0');
        },

        confirmSubmit() {
            if (confirm('Submit now? You cannot change answers later.')) {
                this.submitForm();
            }
        },

        submitForm() {
            const form = document.getElementById('examForm');
            if (!form) return;

            form.dataset.submitting = 'true';
            localStorage.removeItem('exam_end_' + examId);
            form.submit();
        }
    }
}
</script>


@endsection