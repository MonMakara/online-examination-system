@extends('layouts.admin')
@section('title', 'Students Management')

@section('content')
    <div class="px-4 lg:px-8 py-2">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">Students</h1>
                <p class="mt-2 text-sm text-gray-700">A list of all the students in your account including their name, email, and class enrollment status.</p>
            </div>
        </div>

        <div class="mt-8 flex flex-col">
            {{-- Search --}}
            {{-- Search Component --}}
            <div class="mb-6 flex justify-between items-center">
                <div class="relative w-full sm:w-80 group">
                   <form action="{{ route('admin.students.index') }}" method="GET" class="relative w-full">
                        {{-- Preserve other query params --}}
                        @foreach(request()->except(['search', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search students..." 
                            class="w-full pl-11 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 shadow-sm placeholder-gray-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-200 ease-in-out"
                            aria-label="Search students">

                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none group-focus-within:text-indigo-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        @if(request('search'))
                            <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                <a href="{{ route('admin.students.index', request()->except(['search', 'page'])) }}" 
                                    class="p-1 rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors" title="Clear search">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Enrolled Classes</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Joined Date</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($students as $student)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $student->profile_image_url }}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <a href="{{ route('admin.students.show', $student->id) }}" class="font-medium text-gray-900 hover:text-indigo-600 transition-colors">
                                                        {{ $student->name }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $student->email }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $student->enrolled_classes_count }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $student->created_at->format('M d, Y') }}</td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('admin.students.show', $student->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">
                                                    View Details
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 text-center text-sm text-gray-500">No students found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 px-4 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl border-x border-b">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-gray-600">
                        Showing <span class="font-medium">{{ $students->firstItem() ?? 0 }}</span> 
                        to <span class="font-medium">{{ $students->lastItem() ?? 0 }}</span> 
                        of <span class="font-medium">{{ $students->total() }}</span> results
                    </div>

                    <div class="flex space-x-2">
                        @if ($students->onFirstPage())
                            <span class="px-3 py-1 border border-gray-300 rounded-lg bg-gray-50 text-gray-400 cursor-not-allowed text-sm">Previous</span>
                        @else
                            <a href="{{ $students->previousPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 text-sm transition-colors">Previous</a>
                        @endif

                        @if ($students->hasMorePages())
                            <a href="{{ $students->nextPageUrl() }}" class="px-3 py-1 border border-gray-300 rounded-lg bg-white text-gray-700 hover:bg-gray-50 text-sm transition-colors">Next</a>
                        @else
                            <span class="px-3 py-1 border border-gray-300 rounded-lg bg-gray-50 text-gray-400 cursor-not-allowed text-sm">Next</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
