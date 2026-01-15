@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
    <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Teachers</p>
                <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $teachers->count() ?? 0 }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Classes</p>
                <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $classes->count() ?? 0 }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Students</p>
                <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $studentCount ?? 0 }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-xl shadow-xl border border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">Quick Create Class</h2>
                    <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Class Name</label>
                            <input type="text" placeholder="e.g. Science 101" name="name" id="name"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Assign Teacher</label>
                            <select name="teacher_id" id="teacher_id"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="" disabled selected>Select a teacher</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md font-medium hover:bg-indigo-700 transition">
                            Create Class
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900">Active Classrooms</h2>
                        <a href="{{ route('admin.classes.index') }}"
                            class="text-sm text-indigo-600 font-medium hover:underline">
                            View All
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class
                                        Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teacher
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($classes as $class)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $class->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $class->teacher->name ?? 'No teacher' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="font-mono bg-gray-100 px-2 py-1 rounded">
                                                {{ $class->code }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
