@extends('layouts.student')

@section('title', 'Review: ' . $exam->title)

@section('content')
<div class="container mx-auto px-4 lg:px-8 py-8">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Review: {{ $exam->title }}</h1>
            <p class="text-sm text-gray-500">
                Score: <span class="font-bold text-gray-900">{{ $result->score }}%</span> • 
                {{ $result->created_at->format('M d, Y h:i A') }}
            </p>
        </div>
        <a href="{{ route('student.results.index') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm flex items-center">
            &larr; Back to Results
        </a>
    </div>

    <div class="space-y-6">
        @foreach ($exam->questions as $index => $question)
            @php
                // Get the user's specific answer for this question
                $userAnswer = $question->studentAnswers->first()->selected_option ?? null;
                $isCorrect = $userAnswer === $question->correct_option;
                $isSkipped = $userAnswer === null;
            @endphp

            <div class="bg-white rounded-xl border {{ $isCorrect ? 'border-gray-200' : 'border-red-200 ring-1 ring-red-50' }} shadow-sm overflow-hidden">
                
                {{-- Question Header --}}
                <div class="px-6 py-4 {{ $isCorrect ? 'bg-gray-50/50' : 'bg-red-50/50' }} border-b border-gray-100 flex items-start space-x-3">
                    <span class="flex-shrink-0 w-6 h-6 text-xs font-bold rounded flex items-center justify-center mt-0.5 
                        {{ $isCorrect ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1">
                        <div class="text-base text-gray-800 leading-relaxed font-medium">
                            {{ $question->question }}
                        </div>
                        @if(!$isCorrect)
                            <p class="text-xs text-red-600 font-bold mt-1 uppercase tracking-wide">
                                {{ $isSkipped ? 'Skipped' : 'Incorrect Answer' }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Options Grid --}}
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach (['a', 'b', 'c', 'd'] as $letter)
                        @php 
                            $opt = "option_" . $letter; 
                            $isUserSelection = $userAnswer === $letter;
                            $isCorrectAnswer = $question->correct_option === $letter;
                            
                            // Determine styles based on logic
                            $containerClass = "border-gray-200 bg-white";
                            $textClass = "text-gray-700";
                            $icon = "";

                            if ($isCorrectAnswer) {
                                // Always highlight the correct answer in Green
                                $containerClass = "border-emerald-500 bg-emerald-50";
                                $textClass = "text-emerald-900 font-bold";
                                $icon = '<svg class="w-5 h-5 text-emerald-600 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                            } elseif ($isUserSelection && !$isCorrect) {
                                // Highlight wrong user selection in Red
                                $containerClass = "border-red-500 bg-red-50";
                                $textClass = "text-red-900 font-bold";
                                $icon = '<svg class="w-5 h-5 text-red-600 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
                            } elseif ($isUserSelection && $isCorrect) {
                                // User selected correct (already handled by top condition, but explicitly for logic flow)
                                $containerClass = "border-emerald-500 bg-emerald-50";
                            } else {
                                // Dim unrelated options
                                $textClass = "text-gray-500";
                            }
                        @endphp

                        <div class="relative flex items-center p-3 border rounded-lg {{ $containerClass }}">
                            <div class="flex items-center w-full">
                                <span class="w-6 h-6 flex items-center justify-center text-xs font-bold uppercase rounded-full border mr-3
                                    {{ $isCorrectAnswer ? 'bg-emerald-200 text-emerald-800 border-emerald-300' : ($isUserSelection ? 'bg-red-200 text-red-800 border-red-300' : 'bg-gray-100 text-gray-500 border-gray-200') }}">
                                    {{ $letter }}
                                </span>
                                <span class="text-sm {{ $textClass }}">{{ $question->$opt }}</span>
                                {!! $icon !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection