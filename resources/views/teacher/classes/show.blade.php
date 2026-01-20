@extends('layouts.teacher')

@section('title', 'Manage Class: ' . $class->name)

@section('content')
    <div class="space-y-6 px-4 lg:px-8">
        <div class="flex items-center justify-between">
            <a href="{{ route('teacher.classes.index') }}"
                class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                </svg>
                Back to Classrooms
            </a>
            <div class="flex space-x-3">
                <span
                    class="px-4 py-1.5 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold border border-indigo-100">
                    Join Code: {{ $class->code }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Students</p>
                <p class="text-3xl font-black text-gray-800 mt-1">{{ $students->count() }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Exams</p>
                <p class="text-3xl font-black text-indigo-600 mt-1">{{ $activeExamsCount ?? 0 }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Class Average</p>
                <p class="text-3xl font-black text-emerald-500 mt-1">{{ $classAverage ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h2 class="font-bold text-gray-800">Enrolled Students</h2>
                        <div class="relative">
                            <input type="text" placeholder="Search students..."
                                class="text-xs border border-gray-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th
                                        class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                        Student</th>
                                    <th
                                        class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                        Join Date</th>
                                    <th
                                        class="px-6 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($students as $student)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                {{-- Profile Image Logic --}}
                                                <div
                                                    class="h-9 w-9 rounded-full ring-2 ring-gray-100 flex-shrink-0 overflow-hidden">
                                                    @if ($student->profile_image)
                                                        <img src="{{ $student->profile_image }}"
                                                            alt="{{ $student->name }}" class="h-full w-full object-cover">
                                                    @else
                                                        <div
                                                            class="h-full w-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs uppercase">
                                                            {{ substr($student->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="ml-3">
                                                    <span
                                                        class="text-sm font-bold text-gray-800 block leading-tight">{{ $student->name }}</span>
                                                    <span
                                                        class="text-[10px] text-gray-400 font-medium tracking-tight">Student
                                                        ID: #{{ $student->id }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $student->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400">
                                            {{ $student->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <button class="text-indigo-600 hover:text-indigo-900 text-xs font-bold">View
                                                Report</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">
                                            No students have joined this class yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h2 class="font-bold text-gray-800">Class Exams</h2>
                        <a href="{{ route('teacher.exams.create') }}"
                            class="text-xs bg-indigo-600 text-white px-2 py-1 rounded hover:bg-indigo-700 transition">Assign
                            New</a>
                    </div>
                    <div class="p-4 space-y-3">
                        @forelse ($exams as $exam)
                            <div
                                class="p-3 border rounded-lg {{ $exam->isOpen() ? 'border-gray-100' : 'bg-gray-50 opacity-75' }}">
                                <div class="flex justify-between items-start">
                                    <p class="text-sm font-bold {{ $exam->isOpen() ? 'text-gray-800' : 'text-gray-400' }}">
                                        {{ $exam->title }}
                                    </p>
                                    <span
                                        class="h-2 w-2 rounded-full {{ $exam->isOpen() ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                </div>

                                <div class="mt-2 space-y-1">
                                    <p class="text-[10px] text-gray-500">
                                        <span class="font-bold">Due:</span>
                                        {{ $exam->due_at ? $exam->due_at->format('M d, h:i A') : 'No Limit' }}
                                    </p>
                                    <p
                                        class="text-[10px] {{ $exam->closed_at && now()->gt($exam->closed_at) ? 'text-red-500' : 'text-gray-400' }}">
                                        <span class="font-bold">Closes:</span>
                                        {{ $exam->closed_at ? $exam->closed_at->format('M d, h:i A') : 'Manual Only' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-center text-gray-400 py-4">No exams assigned to this class.</p>
                        @endforelse {{-- Ensure this matches the @forelse above --}}
                    </div>
                </div>

                <div class="bg-red-50 rounded-xl border border-red-100 p-6">
                    <h3 class="text-sm font-bold text-red-800">Need Help?</h3>
                    <p class="text-xs text-red-600 mt-1">If students cannot see the class, ensure you have shared the Join
                        Code correctly.</p>
                </div>
            </div>

        </div>
    </div>
@endsection
