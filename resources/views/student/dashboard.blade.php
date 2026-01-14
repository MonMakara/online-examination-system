@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-indigo-600 rounded-2xl p-8 text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-2xl font-bold">Welcome back, </h2>
                <p class="text-indigo-100 mt-2">You have 3 pending exams this week. Good luck!</p>
            </div>
            <div class="absolute -right-10 -top-10 h-40 w-40 bg-white/10 rounded-full"></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-900 mb-4">My Classrooms</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- @forelse($myClasses as $class)
                <div class="p-4 border border-gray-100 rounded-xl hover:border-emerald-500 transition group">
                    <p class="font-bold text-gray-800">{{ $class->name }}</p>
                    <p class="text-xs text-gray-500">Teacher: {{ $class->teacher->name }}</p>
                </div>
                @empty
                <div class="col-span-2 text-center py-6 border-2 border-dashed rounded-xl">
                    <p class="text-gray-400">You haven't joined any classes yet.</p>
                </div>
                @endforelse --}}
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-900 mb-4">Join New Class</h3>
            <form action="" method="POST">
                @csrf
                <input type="text" name="code" placeholder="Enter 6-digit Code" 
                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-center font-mono text-lg uppercase tracking-widest">
                <button class="w-full mt-3 bg-emerald-600 text-white py-3 rounded-lg font-bold hover:bg-emerald-700 transition">
                    Join Classroom
                </button>
            </form>
        </div>
    </div>
</div>
@endsection