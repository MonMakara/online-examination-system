@extends('layouts.admin')

@section('title', 'Edit Classroom')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.classes.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center font-medium">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Classrooms
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-xl font-bold text-gray-800">Class Information</h2>
            <p class="text-sm text-gray-500">Update the name and assigned teacher for this class.</p>
        </div>

        <form action="{{ route('admin.classes.update', $class->id) }}" method="POST" class="p-8 space-y-8">
            @csrf
            @method('PATCH')

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Class Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $class->name) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none shadow-sm"
                        placeholder="e.g. Science 101">
                    @error('name')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="teacher_id" class="block text-sm font-semibold text-gray-700 mb-2">Assigned Teacher</label>
                    <div class="relative">
                        <select name="teacher_id" id="teacher_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white transition outline-none shadow-sm">
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $class->teacher_id == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                    @error('teacher_id')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-6 flex items-center justify-end space-x-4 border-t border-gray-100">
                <a href="{{ route('admin.classes.index') }}" class="px-6 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-10 py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md active:scale-95">
                    Update Class
                </button>
            </div>
        </form>
    </div>
</div>
@endsection