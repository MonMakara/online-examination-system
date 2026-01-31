@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')

@section('content')
    <div class="space-y-6 px-4 lg:px-8">
        {{-- Flash Messages --}}
        <div class="space-y-4">
            @if (session('success'))
                <div class="flex items-center p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm" role="alert">
                    <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="flex items-center p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded-r-lg shadow-sm" role="alert">
                    <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-bold">{{ session('info') }}</span>
                </div>
            @endif

            @if (session('warning'))
                <div class="flex items-center p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm" role="alert">
                    <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-bold">{{ session('warning') }}</span>
                </div>
            @endif
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">My Classes</p>
                <p class="text-3xl font-black text-indigo-600 mt-2">{{ $classes->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Students</p>
                <p class="text-3xl font-black text-indigo-600 mt-2">{{ $totalStudent }}</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Active Exams</p>
                <p class="text-3xl font-black text-indigo-600 mt-2"> {{ $examCount }} </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900 leading-tight">My Classrooms</h2>
                        <a href="{{ route('teacher.classes.index') }}"
                            class="text-xs font-bold text-indigo-600 px-3 py-1 bg-indigo-50 rounded-lg hover:bg-indigo-600 hover:text-white transition">View All</a>
                    </div>
                    
                    <div class="divide-y divide-gray-100">
                        @forelse($classes as $class)
                            <div class="p-6 flex items-center justify-between hover:bg-gray-50/50 transition">
                                <div class="flex items-center space-x-4">
                                    {{-- Class Logo Logic --}}
                                    <div class="h-14 w-14 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center overflow-hidden shrink-0">
                                        @if ($class->logo)
                                            <img src="{{ $class->logo_url }}" class="h-full w-full object-cover">
                                        @else
                                            <span class="text-indigo-600 font-black text-xl">
                                                {{ substr($class->name, 0, 1) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div>
                                        <h3 class="font-black text-gray-900 text-lg leading-tight">{{ $class->name }}</h3>
                                        <div class="flex items-center mt-1 space-x-3">
                                            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-tighter">
                                                Code: <span class="font-mono text-indigo-600">{{ $class->code }}</span>
                                            </p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                                {{ $class->exams_count ?? 0 }} Exams
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-6">
                                    <div class="text-right hidden sm:block">
                                        <div class="flex items-center justify-end -space-x-2 mb-1">
                                            @foreach ($class->students->take(3) as $index => $student)
                                                <div class="h-7 w-7 rounded-full border-2 border-white flex items-center justify-center text-[8px] font-bold text-white shadow-sm overflow-hidden 
                                                    {{ ['bg-indigo-500', 'bg-emerald-500', 'bg-amber-500'][$index] }}"
                                                    title="{{ $student->name }}">
                                                    @if ($student->profile_image)
                                                        <img src="{{ $student->profile_image_url }}" class="h-full w-full object-cover">
                                                    @else
                                                        {{ substr($student->name, 0, 1) }}
                                                    @endif
                                                </div>
                                            @endforeach

                                            @if ($class->students_count > 3)
                                                <div class="h-7 w-7 rounded-full border-2 border-white bg-gray-100 flex items-center justify-center text-[8px] font-bold text-gray-500 shadow-sm">
                                                    +{{ $class->students_count - 3 }}
                                                </div>
                                            @endif
                                        </div>

                                        <p class="text-xs font-black text-gray-900">
                                            {{ $class->students_count }} {{ Str::plural('Student', $class->students_count) }}
                                        </p>
                                        <p class="text-[9px] uppercase tracking-widest text-gray-400 font-black">Enrollment</p>
                                    </div>

                                    <a href="{{ route('teacher.classes.show', $class->id) }}"
                                        class="group p-3 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-sm border border-indigo-100">
                                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
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

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-indigo-600 rounded-2xl p-6 text-white shadow-xl shadow-indigo-100">
                    <h3 class="font-black text-lg mb-2 leading-tight">Create an Exam</h3>
                    <p class="text-indigo-100 text-xs mb-6 opacity-80">Ready to test your students? Build a new quiz or exam now.</p>
                    <a href="{{ route('teacher.exams.create') }}"
                        class="block w-full text-center bg-white text-indigo-600 py-3 rounded-xl font-black text-sm hover:bg-indigo-50 transition active:scale-95">
                        + Start New Exam
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <h3 class="font-black text-gray-900 text-sm uppercase tracking-widest mb-4">System Alerts</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <div class="h-2 w-2 mt-1.5 rounded-full bg-emerald-500 mr-3 shrink-0"></div>
                            <p class="text-xs text-gray-600 leading-relaxed font-medium">Final Semester exams are now available for scheduling.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('[role="alert"]');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        });
    });
</script>