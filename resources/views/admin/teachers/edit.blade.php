@extends('layouts.admin')

@section('title', 'Edit Teacher: ' . $teacher->name)

@section('content')
    <div class="max-w-3xl px-8">
        <div class="mb-6">
            <a href="{{ route('admin.teachers.index') }}"
                class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Back to Teachers List
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                <h2 class="text-xl font-bold text-gray-900">Edit Teacher Information</h2>
                <p class="text-sm text-gray-500">Update the details for account: <strong>{{ $teacher->email }}</strong></p>
            </div>

            {{-- 1. Route points to Update | 2. Method is PUT --}}
            <form action="{{ route('admin.teachers.update', $teacher->id) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" id="name" 
                        value="{{ old('name', $teacher->name) }}" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" id="email" 
                        value="{{ old('email', $teacher->email) }}" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-amber-50 border border-amber-100 p-4 rounded-lg">
                    <h3 class="text-sm font-bold text-amber-800 mb-1">Security Notice</h3>
                    <p class="text-xs text-amber-700">Leave the password fields empty if you do not want to change the current password.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password (Optional)</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <hr class="border-gray-100">

                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.teachers.index') }}"
                        class="text-sm font-medium text-gray-600 hover:text-gray-800">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md">
                        Update Teacher Account
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection