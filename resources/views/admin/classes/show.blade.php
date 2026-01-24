@extends('layouts.admin')
@section('title', $class->name . ' - Class Details')

@section('content')
<div class="px-4 lg:px-8 py-6">
    {{-- Header / Breadcrumb area --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admin.classes.index') }}" class="hover:text-indigo-600 transition-colors">Classes</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Details</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                @if ($class->logo)
                    <img src="{{ $class->logo_url }}" class="h-8 w-8 rounded-lg object-cover">
                @else
                    <div class="h-8 w-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">
                        {{ substr($class->name, 0, 1) }}
                    </div>
                @endif
                {{ $class->name }}
            </h1>
        </div>
        <div>
             <a href="{{ route('admin.classes.edit', $class->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Class
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Class Info & Teacher --}}
        <div class="space-y-6">
            {{-- Class Information --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Class Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Class Name</label>
                        <p class="text-gray-900 font-medium">{{ $class->name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Class Code</label>
                        <div class="flex items-center gap-2">
                            <code class="px-2 py-1 bg-gray-100 rounded text-indigo-600 font-mono font-bold">{{ $class->code }}</code>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Created At</label>
                        <p class="text-gray-600 text-sm">{{ $class->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Teacher Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                 <h2 class="text-lg font-semibold text-gray-900 mb-4">Assigned Teacher</h2>
                 @if($class->teacher)
                    <div class="flex items-center">
                        <img class="h-12 w-12 rounded-full object-cover border border-gray-100" src="{{ $class->teacher->profile_image_url }}" alt="">
                        <div class="ml-3">
                            <p class="text-base font-medium text-gray-900">{{ $class->teacher->name }}</p>
                            <p class="text-sm text-gray-500">{{ $class->teacher->email }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-50">
                        <a href="#" class="text-sm text-indigo-600 font-medium hover:text-indigo-500">View Teacher Profile &rarr;</a>
                    </div>
                 @else
                    <div class="bg-yellow-50 text-yellow-800 p-3 rounded-lg text-sm">
                        No teacher assigned.
                    </div>
                    <div class="mt-4">
                         <a href="{{ route('admin.classes.edit', $class->id) }}" class="text-sm text-indigo-600 font-medium hover:text-indigo-500">Assign Teacher &rarr;</a>
                    </div>
                 @endif
            </div>
        </div>

        {{-- Right Column: Students --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Enrolled Students</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">
                        {{ $class->students->count() }} Total
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <img class="h-8 w-8 rounded-full" src="{{ $student->profile_image_url }}" alt="">
                                            </div>
                                            <div class="ml-3">
                                            <a href="{{ route('admin.students.show', $student->id) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600 transition-colors">
                                                {{ $student->name }}
                                            </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $student->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $student->pivot->created_at ? $student->pivot->created_at->format('M d, Y') : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-sm text-gray-500">
                                        No students enrolled in this class yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($students->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>

            {{-- Exams Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
                 <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Exams</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                        {{ $exams->count() }} Total
                    </span>
                 </div>
                 <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                             <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                             @forelse($exams as $exam)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $exam->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $isClosed = $exam->closed_at && now()->gt($exam->closed_at);
                                        @endphp
                                        @if($isClosed)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                Closed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $exam->due_at ? $exam->due_at->format('M d, Y') : 'No Due Date' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $exam->questions_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div x-data="{ open: false }">
                                            <button @click="open = !open" type="button" class="text-indigo-600 hover:text-indigo-900">
                                                View Results ({{ $exam->results->count() }})
                                            </button>
                                            
                                            {{-- Use a fixed portal or absolute positioning for a simple dropdown-like table, or just inline. --}}
                                            {{-- Since this is inside a table, expanding rows is tricky without proper markup. 
                                                 Let's use a modal or simple alert for now if simpler.
                                                 Actually, let's just make it a simple list that appears below if we weren't invalidating HTML. 
                                                 Better approach: Link to a new 'Exam Results' page? 
                                                 User asked to 'see result'. A Modal is best here. --}}
                                            
                                            <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
                                                aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="open = false"></div>
                                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                                    <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                                        <div>
                                                            <div class="mt-3 text-center sm:mt-5">
                                                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                                    Results: {{ $exam->title }}
                                                                </h3>
                                                                <div class="mt-4 text-left">
                                                                    @if($exam->results->count() > 0)
                                                                    <ul class="divide-y divide-gray-200">
                                                                        @foreach($exam->results as $result)
                                                                        <li class="py-3 flex justify-between">
                                                                            <div class="flex items-center">
                                                                                <img class="h-8 w-8 rounded-full object-cover mr-3" src="{{ $result->student->profile_image_url }}">
                                                                                <div>
                                                                                    <p class="text-sm font-medium text-gray-900">{{ $result->student->name }}</p>
                                                                                    <p class="text-xs text-gray-500">{{ $result->created_at->format('M d, H:i') }}</p>
                                                                                </div>
                                                                            </div>
                                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $result->score >= 50 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                                {{ $result->score }} / 100
                                                                            </span>
                                                                        </li>
                                                                        @endforeach
                                                                    </ul>
                                                                    @else
                                                                        <p class="text-sm text-gray-500 text-center py-4">No submissions yet.</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mt-5 sm:mt-6">
                                                            <button type="button" class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm" @click="open = false">
                                                                Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                             @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                        No exams created for this class yet.
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
