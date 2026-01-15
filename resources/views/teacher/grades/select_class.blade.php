@extends('layouts.teacher')

@section('title', 'Select Class Gradebook')

@section('content')
<div class="px-8 py-6">
    {{-- Header Section --}}
    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Gradebook Directory</h1>
        <p class="text-sm text-gray-500 font-medium">Select a classroom below to view student performance and exam results.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($classes as $class)
            <div class="group bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-start justify-between mb-5">
                    {{-- Fixed Logo Logic --}}
                    <div class="h-14 w-14 bg-gray-50 border border-gray-100 rounded-2xl flex items-center justify-center overflow-hidden shadow-inner shrink-0">
                        @if ($class->logo)
                            <img src="{{ asset('storage/' . $class->logo) }}" class="h-full w-full object-cover" alt="{{ $class->name }}">
                        @else
                            <span class="text-indigo-600 font-black text-xl">
                                {{ substr($class->name, 0, 1) }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="text-right">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Access Code</p>
                        <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100">
                            {{ $class->code }}
                        </span>
                    </div>
                </div>
                
                <h3 class="text-xl font-black text-gray-900 group-hover:text-indigo-600 transition-colors leading-tight mb-2">
                    {{ $class->name }}
                </h3>

                <div class="flex items-center space-x-4 mb-6">
                    <div class="flex items-center text-[11px] font-bold text-gray-500 bg-gray-50 px-2 py-1 rounded-md">
                        <svg class="w-3 h-3 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                        </svg>
                        {{ $class->students_count }} Students
                    </div>
                    <div class="flex items-center text-[11px] font-bold text-gray-500 bg-gray-50 px-2 py-1 rounded-md">
                        <svg class="w-3 h-3 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                        {{ $class->exams_count }} Exams
                    </div>
                </div>

                <a href="{{ route('teacher.grades.class', $class->id) }}" 
                    class="block w-full text-center bg-indigo-600 text-white py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-100 transition-all active:scale-95">
                    View Performance
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection