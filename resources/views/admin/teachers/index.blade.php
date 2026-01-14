@extends('layouts.admin')
@section('title', 'Teachers management')

@section('content')
    <div class="px-8 py-2">
        <div class="space-y-4 mb-6">
            @if (session('success'))
                <div class="flex items-center p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm"
                    role="alert">
                    <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="flex items-center p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 rounded-r-lg shadow-sm"
                    role="alert">
                    <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-bold">{{ session('info') }}</span>
                </div>
            @endif

            @if (session('warning'))
                <div class="flex items-center p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-lg shadow-sm"
                    role="alert">
                    <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-bold">{{ session('warning') }}</span>
                </div>
            @endif
        </div>
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">

            <div class="relative w-full md:w-96">
                <form action="{{ route('admin.teachers.index') }}" method="GET" class="relative w-full md:w-96">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search teachers by name or email..."
                        class="w-full pl-10 pr-12 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">

                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    @if (request('search'))
                        <div class="absolute right-3 top-2">
                            <a href="{{ route('admin.teachers.index') }}"
                                class="text-gray-400 hover:text-gray-600 p-1 bg-gray-100 rounded-full flex items-center justify-center transition"
                                title="Clear search">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </form>
            </div>
            <a href="{{ route('admin.teachers.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shadow-sm">Create
                New Teacher</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-[#656a86] uppercase tracking-wider">
                                ID
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-[#656a86] uppercase tracking-wider">
                                Name
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-[#656a86] uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-[#656a86] uppercase tracking-wider">
                                Classes
                            </th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-[#656a86] uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($teachers as $teacher)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                                    #{{ $teacher->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold mr-3 text-xs">
                                            {{ strtoupper(substr($teacher->name, 0, 2)) }}
                                        </div>
                                        <div class="text-sm font-bold text-gray-900">{{ $teacher->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $teacher->email }}
                                </td>
                                {{-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    @if ($teacher->class_rooms->count() > 0)
                                        @foreach ($teacher->class_rooms as $class)
                                            <span
                                                class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-xs font-medium mr-1 border border-indigo-100">
                                                {{ $class->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-400 italic text-xs">No classes assigned</span>
                                    @endif
                                </td> --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <span
                                        class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-xs font-medium mr-1 border border-indigo-100">
                                        {{-- Use ?-> to safely access count even if the relationship is null --}}
                                        {{ $teacher->managedClasses?->count() ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.teachers.edit', $teacher->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold">Edit</a>
                                    <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between">

                <div class="text-sm text-gray-600">
                    Showing <span class="font-medium">{{ $teachers->firstItem() }}</span>
                    to <span class="font-medium">{{ $teachers->lastItem() }}</span>
                    of <span class="font-medium">{{ $teachers->total() }}</span> results
                </div>
                <div class="flex space-x-2">
                    {{-- Previous Page Link --}}
                    @if ($teachers->onFirstPage())
                        <span
                            class="px-3 py-1 border border-gray-300 rounded bg-white text-gray-400 cursor-not-allowed">Previous</span>
                    @else
                        <a href="{{ $teachers->previousPageUrl() }}"
                            class="px-3 py-1 border border-gray-300 rounded bg-white text-gray-700 hover:bg-gray-50">Previous</a>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($teachers->hasMorePages())
                        <a href="{{ $teachers->nextPageUrl() }}"
                            class="px-3 py-1 border border-gray-300 rounded bg-white text-gray-700 hover:bg-gray-50">Next</a>
                    @else
                        <span
                            class="px-3 py-1 border border-gray-300 rounded bg-white text-gray-400 cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Find all alert divs
        const alerts = document.querySelectorAll('[role="alert"]');
        
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }, 3000); // 3 seconds
        });
    });
</script>
