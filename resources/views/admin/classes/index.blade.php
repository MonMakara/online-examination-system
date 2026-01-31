@extends('layouts.admin')
@section('title', 'Classes management')

@section('content')
    <div class="px-4 lg:px-8">
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

        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">

            {{-- Search Component --}}
            <div class="relative w-full sm:w-80 group">
                <form action="{{ route('admin.classes.index') }}" method="GET" class="relative w-full">

                    {{-- 1. Preserve other query params (like sort, limit, etc.) EXCEPT search and page --}}
                    @foreach (request()->except(['search', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    {{-- Input Field --}}
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search classes..."
                        class="w-full pl-11 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 shadow-sm placeholder-gray-400
                       focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 ease-in-out"
                        aria-label="Search classes">

                    {{-- Search Icon (Decorative) --}}
                    <div
                        class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-focus-within:text-indigo-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    {{-- Clear Button (Only visible if searching) --}}
                    @if (request('search'))
                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                            {{-- We intentionally remove 'search' and 'page' params to reset the view --}}
                            <a href="{{ route('admin.classes.index', request()->except(['search', 'page'])) }}"
                                class="p-1 rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors"
                                title="Clear search">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Create Button --}}
            <a href="{{ route('admin.classes.create') }}"
                class="inline-flex items-center gap-2 bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                <span>Create Class</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($classes as $class)
                <a href="{{ route('admin.classes.show', $class->id) }}" class="block group">
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200 h-full flex flex-col">
                        <div class="p-5 flex-1">
                            <div class="flex items-start justify-between">
                                <div
                                    class="h-12 w-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center overflow-hidden">
                                    @if ($class->logo)
                                        <img src="{{ $class->logo_url }}" class="h-full w-full object-cover">
                                    @else
                                        <span
                                            class="text-indigo-600 font-bold text-xl">{{ substr($class->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-1">
                                    {{-- Edit Button --}}
                                    <object>
                                        <a href="{{ route('admin.classes.edit', $class->id) }}"
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                                            title="Edit Class">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                    </object>

                                    {{-- Delete Button --}}
                                    <object>
                                        <button type="button"
                                            @click.stop.prevent="$dispatch('open-delete-modal', { action: '{{ route('admin.classes.destroy', $class->id) }}', title: 'Delete Class', message: 'Are you sure you want to delete {{ $class->name }}?' })"
                                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
                                            title="Delete Class">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </object>
                                </div>
                            </div>

                            <h3 class="mt-4 text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-1"
                                title="{{ $class->name }}">
                                {{ $class->name }}
                            </h3>

                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <span
                                    class="font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs border border-gray-200 mr-2">
                                    {{ $class->code }}
                                </span>
                                <span>ID: #{{ $class->id }}</span>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="h-6 w-6 rounded-full bg-gray-200 overflow-hidden mr-2">
                                        @if ($class->teacher && $class->teacher->profile_image)
                                            <img src="{{ $class->teacher->profile_image_url }}"
                                                class="h-full w-full object-cover">
                                        @else
                                            <div
                                                class="h-full w-full flex items-center justify-center bg-indigo-100 text-indigo-600 text-[10px] font-bold">
                                                {{ strtoupper(substr($class->teacher->name ?? '?', 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 truncate max-w-[100px]"
                                        title="{{ $class->teacher->name ?? 'Unassigned' }}">
                                        {{ $class->teacher->name ?? 'Unassigned' }}
                                    </span>
                                </div>

                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $class->students_count > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $class->students_count }} Students
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-xl border border-gray-200 border-dashed">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No classes found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new class.</p>
                    <div class="mt-6">
                        <a href="{{ route('admin.classes.create') }}"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Create Class
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-8 flex justify-center">
            {{ $classes->appends(request()->except('page'))->links() }}
        </div>
    </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('[role="alert"]');

        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = "opacity 0.5s ease";
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        });
    });
</script>
