@extends('layouts.student')

@section('title', $exam->title)

@section('content')
    {{-- Initialize the exam timer with duration and exam metadata --}}
    <div class="container mx-auto px-4 lg:px-8 py-8 pb-24" x-data="examTimer({{ $exam->duration * 60 }}, '{{ $examClosedAt }}', '{{ $examDueAt }}', {{ $exam->id }}, {{ auth()->id() }})">

        {{-- Sticky Header --}}
        <div
            class="sticky top-20 z-20 bg-white shadow-md border border-gray-200 rounded-xl p-5 mb-8 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="p-2.5 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mb-1">Time
                        Remaining</p>
                    {{-- Updated to use text property --}}
                    <p class="text-2xl font-bold text-blue-600 leading-none" x-text="timerDisplay"></p>
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <div class="text-right hidden sm:block mr-2">
                    <p class="text-xs font-bold text-gray-800">{{ $exam->title }}</p>
                    <p class="text-[10px] text-gray-400 uppercase tracking-tighter">Total Questions:
                        {{ $exam->questions->count() }}</p>
                </div>
                <button type="button" @click="confirmSubmit()"
                    class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-blue-700 transition shadow-sm">
                    Submit
                </button>
            </div>
        </div>

        {{-- Question List --}}
        <form id="examForm" action="{{ route('student.exams.submit', $exam->id) }}" method="POST">
            @csrf
            <div class="space-y-6">
                @foreach ($exam->questions as $index => $question)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        {{-- Question Header --}}
                        <div class="px-6 py-5 bg-gray-50 border-b border-gray-100 flex items-start gap-4">
                            <span
                                class="flex-shrink-0 w-8 h-8 bg-white border border-blue-100 text-blue-600 text-sm font-bold rounded-lg flex items-center justify-center shadow-sm">
                                {{ $index + 1 }}
                            </span>
                            <div class="text-lg font-medium text-gray-900 leading-relaxed font-sans">
                                {{ $question->question }}
                            </div>
                        </div>

                        {{-- Options Grid --}}
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach (['a', 'b', 'c', 'd'] as $letter)
                                    @php $opt = "option_" . $letter; @endphp
                                    <label
                                        class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-blue-50/50 hover:border-blue-200 transition group">
                                        <input type="radio" name="answers[{{ $question->id }}]"
                                            value="{{ $letter }}"
                                            {{ old("answers.$question->id") == $letter ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <div class="ml-4">
                                            <span
                                                class="text-xs font-bold text-gray-400 uppercase mr-1">{{ $letter }}.</span>
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
    {{-- Modal was previously outside scope --}}

    <div
        x-show="showConfirmModal"
        style="display: none;"
        class="relative z-50">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div @click.outside="if(modalMode !== 'alert' || modalTitle !== 'Time is Up!') showConfirmModal = false" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-full max-w-lg mx-4 sm:mx-auto">
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                             <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10"
                                :class="modalMode === 'alert' ? 'bg-red-100' : 'bg-blue-100'">
                                <template x-if="modalMode === 'alert'">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                </template>
                                <template x-if="modalMode !== 'alert'">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </template>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-base font-semibold leading-6 text-gray-900" x-text="modalTitle"></h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500" x-text="confirmMessage"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <template x-if="modalMode === 'confirm'">
                            <div class="contents">
                                <button type="button" @click="submitForm()" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Submit Exam</button>
                                <button type="button" @click="showConfirmModal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                            </div>
                        </template>
                        <template x-if="modalMode === 'alert'">
                            <button type="button" 
                                @click="showConfirmModal = false" 
                                x-show="modalTitle !== 'Time is Up!'"
                                class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">
                                OK
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function examTimer(durationInSeconds, closedAtString, dueAtString, examId, userId) {
            return {
                timerDisplay: '00:00',
                endTime: null,
                interval: null,
                showConfirmModal: false,
                confirmMessage: '',
                completionStatus: 'complete',
                modalMode: 'confirm', // Can be 'confirm' (for submission) or 'alert' (for warnings)
                modalTitle: '',

                init() {
                    const storageKey = 'exam_timer_' + examId + '_' + userId;
                    const answersStorageKey = 'exam_answers_' + examId + '_' + userId;
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

                    // Check if there is an existing timer saved in localStorage to prevent reset on refresh
                    const savedEndTime = parseInt(localStorage.getItem(storageKey));
                    if (savedEndTime && savedEndTime < calculatedEndTime) {
                        this.endTime = savedEndTime;
                    } else {
                        this.endTime = calculatedEndTime;
                        localStorage.setItem(storageKey, this.endTime);
                    }
                    
                    // --- Restore Saved Answers ---
                    // This ensures students don't lose their selected options if they refresh the page
                    try {
                        const savedAnswers = JSON.parse(localStorage.getItem(answersStorageKey) || '{}');
                        Object.keys(savedAnswers).forEach(questionId => {
                            const value = savedAnswers[questionId];
                            const radioBtn = document.querySelector(`input[name="answers[${questionId}]"][value="${value}"]`);
                            if (radioBtn) {
                                radioBtn.checked = true;
                            }
                        });
                    } catch (e) {
                        console.error("Error restoring answers:", e);
                    }

                    // Watch for changes (using event delegation on form)
                    const form = document.getElementById('examForm');
                    if(form) {
                        form.addEventListener('change', (e) => {
                            if (e.target.name && e.target.name.startsWith('answers[')) {
                                const qIdMatch = e.target.name.match(/answers\[(\d+)\]/);
                                if (qIdMatch && qIdMatch[1]) {
                                    const qId = qIdMatch[1];
                                    const val = e.target.value;
                                    
                                    // Save to local storage
                                    const currentSaved = JSON.parse(localStorage.getItem(answersStorageKey) || '{}');
                                    currentSaved[qId] = val;
                                    localStorage.setItem(answersStorageKey, JSON.stringify(currentSaved));
                                }
                            }
                        });
                    }
                    // End restoration logic

                    this.tick();
                    this.interval = setInterval(() => this.tick(), 1000);
                    
                    // Tab Switching Detection
                    document.addEventListener('visibilitychange', () => {
                        if (document.hidden) {
                            this.handleVisibilityChange();
                        }
                    });
                },

                handleVisibilityChange() {
                    this.modalMode = 'alert';
                    this.modalTitle = 'Warning: Tab Switch Detected';
                    this.confirmMessage = 'You are not allowed to switch tabs or minimize the browser during the exam. Please stay on this screen.';
                    this.showConfirmModal = true;
                },

                tick() {
                    const distance = this.endTime - Date.now();

                    if (distance <= 0) {
                        this.timerDisplay = "00:00:00";
                        clearInterval(this.interval);

                        const form = document.getElementById('examForm');
                        if (form && form.dataset.submitting !== 'true') {
                            this.modalMode = 'alert';
                            this.modalTitle = 'Time is Up!';
                            this.confirmMessage = 'Your exam time has ended. Submitting your answers now...';
                            this.showConfirmModal = true;
                            
                            setTimeout(() => {
                                this.submitForm();
                            }, 2000);
                        }
                        return;
                    }

                    const hours = Math.floor(distance / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    if (hours > 0) {
                        this.timerDisplay =
                            hours.toString().padStart(2, '0') + ':' +
                            minutes.toString().padStart(2, '0') + ':' +
                            seconds.toString().padStart(2, '0');
                    } else {
                        this.timerDisplay =
                            minutes.toString().padStart(2, '0') + ':' +
                            seconds.toString().padStart(2, '0');
                    }
                },

                confirmSubmit() {
                    const form = document.getElementById('examForm');
                    const totalQuestions = {{ $exam->questions->count() }};
                    
                    // Count answered questions
                    const formData = new FormData(form);
                    let answeredCount = 0;
                    for (let pair of formData.entries()) {
                        if (pair[0].startsWith('answers[')) {
                            answeredCount++;
                        }
                    }

                    if (answeredCount === 0) {
                        this.modalMode = 'alert';
                        this.modalTitle = 'No Answers Selected';
                        this.confirmMessage = 'You have not answered any questions. Please select at least one answer before submitting.';
                        this.showConfirmModal = true;
                        return;
                    }

                    this.modalMode = 'confirm'; // Reset to confirm mode
                    this.modalTitle = 'Confirm Submission'; // Default title

                    if (answeredCount < totalQuestions) {
                         const remaining = totalQuestions - answeredCount;
                         this.completionStatus = 'incomplete';
                         this.modalTitle = 'Incomplete Submission';
                         this.confirmMessage = `You have ${remaining} unanswered question(s). Are you sure you want to submit?`;
                    } else {
                        this.completionStatus = 'complete';
                        this.confirmMessage = 'Are you sure you want to submit your exam?';
                    }
                    
                    this.showConfirmModal = true;
                },

                submitForm() {
                    const form = document.getElementById('examForm');
                    if (!form) return;

                    form.dataset.submitting = 'true';
                    localStorage.removeItem('exam_timer_' + examId + '_' + userId);
                    // Also clear answers on submit
                    localStorage.removeItem('exam_answers_' + examId + '_' + userId);
                    form.submit();
                }
            }
        }
    </script>
@endsection
