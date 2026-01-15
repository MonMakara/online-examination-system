@extends('layouts.admin')
@section('title', 'Teachers Management')

@section('content')
    <div class="px-4 lg:px-8 py-2">
        {{-- Flash Messages (Keep your existing script for auto-hide) --}}
        <div class="space-y-4 mb-6">
            @if (session('success'))
                <div role="alert"
                    class="flex items-center p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl shadow-sm">
                    <svg class="h-5 w-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif
            {{-- ... other session checks (info/warning) ... --}}
        </div>

        {{-- Header & Search --}}
        <div class="mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="relative w-full md:w-96">
                <form action="{{ route('admin.teachers.index') }}" method="GET">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name or email..."
                        class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition shadow-sm text-sm">
                    <div class="absolute left-4 top-3 text-gray-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </form>
            </div>
            <a href="{{ route('admin.teachers.create') }}"
                class="inline-flex items-center bg-indigo-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-100 active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Teacher
            </a>
        </div>

        {{-- Table Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Teacher Profile</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Email Address</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Managed Classes</th>
                            <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($teachers as $teacher)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-8 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        {{-- Profile Photo Logic --}}
                                        <div
                                            class="h-10 w-10 rounded-full border-2 border-white shadow-sm bg-indigo-50 flex items-center justify-center overflow-hidden flex-shrink-0">
                                            @if ($teacher->profile_image)
                                                <img src="{{ asset('storage/' . $teacher->profile_image) }}"
                                                    class="h-full w-full object-cover">
                                            @else
                                                <span class="text-indigo-600 font-black text-xs">
                                                    {{ strtoupper(substr($teacher->name, 0, 2)) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition">
                                                {{ $teacher->name }}</div>
                                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">ID:
                                                #{{ $teacher->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600">{{ $teacher->email }}</span>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span
                                            class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-black rounded-lg border border-indigo-100">
                                            {{ $teacher->managedClasses?->count() ?? 0 }} Classes
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end items-center space-x-2">
                                        <a href="{{ route('admin.teachers.edit', $teacher->id) }}"
                                            class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition bg-white border border-gray-100 shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this teacher?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition bg-white border border-gray-100 shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center">
                                    <p class="text-gray-400 font-medium">No teachers found matching your criteria.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                    Showing {{ $teachers->firstItem() }} - {{ $teachers->lastItem() }} of {{ $teachers->total() }}
                </div>
                <div class="flex space-x-1">
                    @if (!$teachers->onFirstPage())
                        <a href="{{ $teachers->previousPageUrl() }}"
                            class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 transition">Prev</a>
                    @endif
                    @if ($teachers->hasMorePages())
                        <a href="{{ $teachers->nextPageUrl() }}"
                            class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 hover:bg-gray-50 transition">Next</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
