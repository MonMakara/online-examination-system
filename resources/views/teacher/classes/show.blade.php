@extends('layouts.teacher')

@section('title', 'Classroom: ' . $class->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('teacher.classes.index') }}" class="text-sm text-indigo-600 font-bold flex items-center">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Classrooms
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h2 class="text-lg font-bold text-gray-900">Enrolled Students</h2>
        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold">{{ $students->count() }} Total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Student Name</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Joined Date</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($students as $student)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $student->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $student->pivot->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <button class="text-indigo-600 hover:text-indigo-900 text-xs font-bold uppercase tracking-wider">View Results</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection