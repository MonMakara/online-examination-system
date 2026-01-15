@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')

@section('content')
    <div class="space-y-8 px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">My Classes</p>
                <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $classes->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Students</p>
                <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $totalStudent }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Exams</p>
                <p class="text-3xl font-bold text-indigo-600 mt-2"> {{ $examCount }} </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900">My Classrooms</h2>
                        <a href="es.index') " class="text-sm text-indigo-600 font-medium hover:underline">View All</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="divide-y divide-gray-100">
                                @forelse($classes as $class)
                                    <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="h-12 w-12 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-lg">
                                                {{ substr($class->name, 0, 1) }}
                                            </div>

                                            <div>
                                                <h3 class="font-bold text-gray-900 text-lg leading-tight">
                                                    {{ $class->name }}</h3>
                                                <div class="flex items-center mt-1 space-x-3">
                                                    <p class="text-xs text-gray-500">
                                                        Join Code: <span
                                                            class="font-mono font-bold text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded">{{ $class->code }}</span>
                                                    </p>
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                                            <path fill-rule="evenodd"
                                                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $class->exams_count ?? 0 }} Exams
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-6">
                                            <div class="text-right hidden sm:block">
                                                <div class="flex items-center justify-end -space-x-2 mb-1">
                                                    <div
                                                        class="h-6 w-6 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-[8px]">
                                                        1</div>
                                                    <div
                                                        class="h-6 w-6 rounded-full border-2 border-white bg-gray-300 flex items-center justify-center text-[8px]">
                                                        2</div>
                                                    <div
                                                        class="h-6 w-6 rounded-full border-2 border-white bg-gray-400 flex items-center justify-center text-[8px]">
                                                        3</div>
                                                </div>
                                                <p class="text-sm font-bold text-gray-900">{{ $class->students_count }}
                                                    Students</p>
                                                <p class="text-[10px] uppercase tracking-wider text-gray-400 font-semibold">
                                                    Active Enrollment</p>
                                            </div>

                                            <a href="{{ route('teacher.classes.show', $class->id) }}"
                                                class="group p-3 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-sm">
                                                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-12 text-center">
                                        <p class="text-gray-400 italic">No classes assigned to you yet.</p>
                                    </div>
                                @endforelse
                            </div>

                            @if ($classes->hasPages())
                                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                                    {{ $classes->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-indigo-600 rounded-xl p-6 text-white shadow-lg">
                    <h3 class="font-bold text-lg mb-2">Create an Exam</h3>
                    <p class="text-indigo-100 text-sm mb-4">Ready to test your students? Build a new quiz or exam now.</p>
                    <a href="{{ route('teacher.exams.create') }}"
                        class="block w-full text-center bg-white text-indigo-600 py-2 rounded-lg font-bold hover:bg-indigo-50 transition">
                        + Start New Exam
                    </a>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-4">System Alerts</h3>
                    <ul class="space-y-3">
                        <li class="text-sm flex items-start">
                            <span class="h-2 w-2 mt-1.5 rounded-full bg-green-500 mr-2 shrink-0"></span>
                            <span class="text-gray-600">Final Semester exams are now available for scheduling.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
