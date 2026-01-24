@extends('layouts.admin')
@section('title', 'Student Details')

@section('content')
<div class="px-4 lg:px-8 py-6">
    <div class="mb-6 flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.students.index') }}" class="hover:text-indigo-600 transition-colors">Students</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">Details</span>
    </div>

    {{-- Header Profile Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center text-3xl font-bold text-indigo-600 border-4 border-white shadow-md overflow-hidden">
                 @if ($student->profile_image)
                    <img src="{{ $student->profile_image_url }}" class="h-full w-full object-cover">
                @else
                    {{ substr($student->name, 0, 1) }}
                @endif
            </div>
            <div class="text-center md:text-left flex-1">
                <h1 class="text-2xl font-bold text-gray-900">{{ $student->name }}</h1>
                <p class="text-gray-500">{{ $student->email }}</p>
                <div class="flex items-center justify-center md:justify-start gap-2 mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                        Student ID: #{{ $student->id }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                        Joined {{ $student->created_at->format('M Y') }}
                    </span>
                </div>
            </div>
            {{-- Quick Stats --}}
            <div class="flex gap-6 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6 w-full md:w-auto justify-around md:justify-start">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $student->enrolledClasses->count() }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Classes</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $student->results->count() }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Exams Taken</div>
                </div>
                 <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $student->results->count() > 0 ? round($student->results->avg('score')) . '%' : '-' }}</div>
                    <div class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Avg Score</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Enrolled Classes --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Enrolled Classes</h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($student->enrolledClasses as $class)
                        <div class="p-4 hover:bg-gray-50 transition-colors flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold shrink-0">
                                {{ substr($class->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('admin.classes.show', $class->id) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600 truncate block">
                                    {{ $class->name }}
                                </a>
                                <p class="text-xs text-gray-500">Code: {{ $class->code }}</p>
                            </div>
                        </div>
                    @empty
                         <div class="p-8 text-center text-sm text-gray-500">
                            Not enrolled in any classes.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column: Exam Results --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Exam Results History</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($student->results as $result)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $result->exam->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $result->exam->classRoom->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $scoreColor = 'text-gray-900';
                                            if($result->score >= 80) $scoreColor = 'text-emerald-600 font-bold';
                                            elseif($result->score >= 50) $scoreColor = 'text-blue-600 font-bold';
                                            else $scoreColor = 'text-red-500 font-bold';
                                        @endphp
                                        <div class="text-sm {{ $scoreColor }}">{{ $result->score }}%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $result->created_at->format('M d, Y') }}
                                        <span class="text-xs text-gray-400 block">{{ $result->created_at->format('h:i A') }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No exams taken yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
