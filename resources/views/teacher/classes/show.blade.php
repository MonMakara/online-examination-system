@extends('layouts.teacher')

@section('title', 'Manage Class: ' . $class->name)

@section('content')
    <div class="space-y-6 px-8">
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
                                                <div
                                                    class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs mr-3">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                                <span class="text-sm font-bold text-gray-800">{{ $student->name }}</span>
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
                        @forelse($exams as $exam)
                            <div
                                class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:border-indigo-200 transition group">
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $exam->title }}</p>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-tighter">
                                        {{ $exam->questions_count }} Questions</p>
                                </div>
                                <span
                                    class="h-2 w-2 rounded-full {{ $exam->is_active ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                            </div>
                        @empty
                            <p class="text-xs text-center text-gray-400 py-4">No exams assigned to this class.</p>
                        @endforelse
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
