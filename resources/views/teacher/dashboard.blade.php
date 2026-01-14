@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="space-y-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">My Classes</p>
            <p class="text-3xl font-bold text-indigo-600 mt-2"></p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Students</p>
            <p class="text-3xl font-bold text-indigo-600 mt-2"></p>
        </div>
        <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
            <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Exams</p>
            <p class="text-3xl font-bold text-indigo-600 mt-2"> 0 </p>
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
                    {{-- @forelse($classes as $class)
                    <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition">
                        <div>
                            <h3 class="font-bold text-gray-900">
                            <p class="text-xs text-gray-500">Join Code: <span class="font-mono font-bold text-indigo-600">n></p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-gray-900">nt ?? 0  Students</p>
                                <p class="text-xs text-gray-400">Assigned by Admin</p>
                            </div>
                            <a href="es.show', $class->id) " class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="p-10 text-center text-gray-400">No classes assigned to you yet.</div>
                    @endforelse --}}
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-indigo-600 rounded-xl p-6 text-white shadow-lg">
                <h3 class="font-bold text-lg mb-2">Create an Exam</h3>
                <p class="text-indigo-100 text-sm mb-4">Ready to test your students? Build a new quiz or exam now.</p>
                <a href=".create') " class="block w-full text-center bg-white text-indigo-600 py-2 rounded-lg font-bold hover:bg-indigo-50 transition">
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