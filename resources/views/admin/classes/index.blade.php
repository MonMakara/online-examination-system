@extends('layouts.admin')
@section('title', 'Classes management')

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
        <div class="mb-6 flex justify-between items-end">
            <div class="relative w-72">
                <form action="{{ route('admin.classes.index') }}" method="GET" class="relative w-72">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search class by name..."
                        class="w-full pl-10 {{ request('search') ? 'pr-10' : 'pr-4' }} py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-sm">

                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    @if (request('search'))
                        <div class="absolute right-2 top-2">
                            <a href="{{ route('admin.classes.index') }}"
                                class="flex items-center justify-center h-6 w-6 text-gray-400 hover:bg-gray-100 hover:text-gray-600 rounded-full transition-colors"
                                title="Clear search">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </form>
            </div>
            <a href="{{ route('admin.classes.create') }}"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shadow-sm">Create
                New Class</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                Class Name
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                Assigned Teacher
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                Join Code
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">
                                Students
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($classes as $class)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $class->name }}</div>
                                    <div class="text-xs text-gray-500">Class ID: #{{ $class->id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-7 w-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-[10px] font-bold mr-2">
                                            {{-- Logic to get initials --}}
                                            {{ strtoupper(substr($class->teacher->name ?? 'N/A', 0, 2)) }}
                                        </div>
                                        <div class="text-sm text-gray-600">{{ $class->teacher->name ?? 'Unassigned' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="font-mono bg-indigo-50 text-indigo-700 px-3 py-1 rounded-md text-sm font-bold border border-indigo-100">
                                        {{ $class->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $class->students_count }} Students
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.classes.edit', $class->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold">Edit</a>
                                    <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold"
                                            onclick="return confirm('Delete this class?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">No classes found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        Showing <span class="font-medium">{{ $classes->firstItem() ?? 0 }}</span>
                        to <span class="font-medium">{{ $classes->lastItem() ?? 0 }}</span>
                        of <span class="font-medium">{{ $classes->total() }}</span> results
                    </div>

                    <div class="flex space-x-2">
                        @if ($classes->onFirstPage())
                            <span
                                class="px-3 py-1 border border-gray-300 rounded bg-white text-gray-400 cursor-not-allowed text-sm">Previous</span>
                        @else
                            <a href="{{ $classes->previousPageUrl() }}"
                                class="px-3 py-1 border border-gray-300 rounded bg-white text-gray-700 hover:bg-gray-50 text-sm">Previous</a>
                        @endif

                        @if ($classes->hasMorePages())
                            <a href="{{ $classes->nextPageUrl() }}"
                                class="px-3 py-1 border border-gray-300 rounded bg-white text-gray-700 hover:bg-gray-50 text-sm">Next</a>
                        @else
                            <span
                                class="px-3 py-1 border border-gray-300 rounded bg-white text-gray-400 cursor-not-allowed text-sm">Next</span>
                        @endif
                    </div>
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