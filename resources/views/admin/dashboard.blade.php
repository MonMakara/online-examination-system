@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
    <div class="p-8 space-y-8">
        {{-- Stats Section --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $statCards = [
                    ['label' => 'Teachers', 'count' => $teachers->count(), 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197'],
                    ['label' => 'Classes', 'count' => $classes->count(), 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['label' => 'Students', 'count' => $studentCount ?? 0, 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222']
                ];
            @endphp
            @foreach($statCards as $card)
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">{{ $card['label'] }}</p>
                        <p class="text-3xl font-black text-indigo-900 mt-1">{{ $card['count'] }}</p>
                    </div>
                    <div class="h-12 w-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}" />
                        </svg>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Form Section --}}
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-8">
                    <h2 class="text-lg font-bold text-gray-900 mb-1">Quick Create Class</h2>
                    <p class="text-xs text-gray-500 mb-6">Instantly setup a new classroom.</p>
                    
                    {{-- Added enctype for file support --}}
                    <form action="{{ route('admin.classes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Class Name</label>
                            <input type="text" placeholder="e.g. Science 101" name="name" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Assign Teacher</label>
                            <select name="teacher_id" required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm">
                                <option value="" disabled selected>Select a teacher</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Class Logo</label>
                            <input type="file" name="logo" accept="image/*"
                                class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                        </div>
                        <button type="submit"
                            class="w-full bg-indigo-600  text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-gray-200 active:scale-95">
                            Create Classroom
                        </button>
                    </form>
                </div>
            </div>

            {{-- Table Section --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-white">
                        <h2 class="text-lg font-bold text-gray-900">Active Classrooms</h2>
                        <a href="{{ route('admin.classes.index') }}"
                            class="text-xs font-bold text-indigo-600 px-3 py-1 bg-indigo-50 rounded-lg hover:bg-indigo-600 hover:text-white transition">
                            View All Classes
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Classroom Info</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Assigned Teacher</th>
                                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Join Code</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($classes as $class)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                {{-- Logo/Avatar --}}
                                                <div class="h-10 w-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                                                    @if($class->logo)
                                                        <img src="{{ asset('storage/' . $class->logo) }}" class="h-full w-full object-cover">
                                                    @else
                                                        <span class="text-indigo-600 font-bold text-sm">{{ substr($class->name, 0, 1) }}</span>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <p class="text-sm font-bold text-gray-900 leading-tight">{{ $class->name }}</p>
                                                    <p class="text-[10px] text-gray-400 font-medium">Class ID: #{{ $class->id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-6 w-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] text-gray-500 font-bold mr-2">
                                                    {{ substr($class->teacher->name ?? '?', 0, 1) }}
                                                </div>
                                                <span class="text-sm text-gray-600 font-medium">{{ $class->teacher->name ?? 'No teacher' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="font-mono text-xs font-bold bg-indigo-50 text-indigo-700 px-3 py-1 rounded-lg border border-indigo-100">
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