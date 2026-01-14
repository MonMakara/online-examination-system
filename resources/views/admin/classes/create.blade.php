@extends('layouts.admin')

@section('title', 'Create New Class')

@section('content')
<div class="max-w-5xl">
    
    <div class="mb-6">
        <a href="{{route('admin.classes.index')}}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center font-medium transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Classrooms
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-xl font-bold text-gray-800">Class Details</h2>
            <p class="text-sm text-gray-500">Setup a new classroom and assign a lead teacher.</p>
        </div>

        <form action="{{route('admin.classes.store')}}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Classroom Name</label>
                        <input type="text" name="name" id="name" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm outline-none"
                            placeholder="e.g. Grade 10 - Advanced Mathematics">
                        @error('name')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="teacher_id" class="block text-sm font-semibold text-gray-700 mb-2">Assign Teacher</label>
                        <div class="relative">
                            <select name="teacher_id" id="teacher_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white transition shadow-sm outline-none">
                                <option value="" disabled selected>Select a teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50/50 border border-indigo-100 p-6 rounded-xl">
                    <h3 class="text-indigo-800 font-bold mb-2 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                        About Join Codes
                    </h3>
                    <p class="text-sm text-indigo-700 leading-relaxed">
                        Once created, the system generates a unique <strong>6-character alphanumeric code</strong>. 
                        Give this code to your students so they can join this specific classroom.
                    </p>
                </div>
            </div>

            <div class="pt-6 flex items-center justify-end space-x-4 border-t border-gray-100">
                <a href="{{route('admin.classes.index')}}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-10 py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md active:scale-95">
                    Create Classroom
                </button>
            </div>
        </form>
    </div>
</div>
@endsection