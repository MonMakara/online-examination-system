@extends('layouts.admin')
@section('title', 'Students Management')

@section('content')
    <div class="px-4 lg:px-8 py-6">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold text-gray-900">Students</h1>
                <p class="mt-2 text-sm text-gray-600">A list of all students including their name, email, and class enrollment status.</p>
            </div>
        </div>

        <div class="mt-8">
            {{-- Search Bar --}}
            <div class="mb-6">
                <form action="{{ route('admin.students.index') }}" method="GET" class="relative max-w-sm group">
                    {{-- Keep other filters if you add them later --}}
                    @foreach(request()->except(['search', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." 
                        class="w-full pl-11 pr-10 py-2.5 bg-white border border-gray-300 rounded-xl text-sm focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none"
                        aria-label="Search students">

                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    @if(request('search'))
                        <div class="absolute right-3 top-1/2 -translate-y-1/2">
                            <a href="{{ route('admin.students.index') }}" class="p-1 rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600" title="Clear search">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Table Container --}}
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg bg-white">
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
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full object-cover shadow-sm" src="{{ $student->profile_image_url }}" alt="">
                                        <div class="ml-4">
                                            <a href="{{ route('admin.students.show', $student->id) }}" class="font-semibold text-gray-900 hover:text-indigo-600 transition-colors">
                                                {{ $student->name }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-600">{{ $student->email }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-center sm:text-left">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $student->enrolled_classes_count }} Classes
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $student->created_at->format('M d, Y') }}</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="{{ route('admin.students.show', $student->id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-2 rounded-lg transition-all">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-sm text-gray-500 italic">
                                    No students found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Clean Pagination Wrapper --}}
                @if($students->hasPages())
                    <div class="bg-white px-4 py-4 border-t border-gray-200 sm:px-6">
                        {{ $students->onEachSide(1)->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection