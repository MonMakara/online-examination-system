@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
    <div class="mb-6 px-8">
        <a href="{{ route('admin.dashboard') }}"
            class="inline-flex items-center text-xs font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 transition-colors group">
            <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
            </svg>
            Back to Dashboard
        </a>
    </div>

    <div class="max-w-4xl mx-auto pb-12 px-8">
        {{-- Modern Toast Alerts --}}
        <div class="fixed top-24 right-8 z-50 space-y-4 min-w-[320px]">
            @if (session('success'))
                <div role="alert" class="alert-toast flex items-center p-4 bg-white border border-emerald-100 rounded-2xl shadow-xl shadow-emerald-100/50 text-emerald-700">
                    <div class="bg-emerald-500 p-1.5 rounded-lg mr-3">
                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-sm font-black uppercase tracking-tight">{{ session('success') }}</span>
                </div>
            @endif
            {{-- Add similar logic for 'info' and 'warning' if needed --}}
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Profile Photo Section --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Avatar Settings</h2>
                </div>
                <div class="p-8 flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-8">
                    <div class="relative group">
                        <div class="h-24 w-24 rounded-3xl bg-indigo-50 border-4 border-white shadow-md overflow-hidden shrink-0 transition-transform group-hover:scale-105">
                            <img id="preview" class="h-full w-full object-cover"
                                src="{{ $user->profile_image_url }}"
                                alt="Current profile photo">
                        </div>
                        <div class="absolute -bottom-2 -right-2 bg-white p-1.5 rounded-xl shadow-lg border border-gray-100 text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    </div>
                    
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-sm font-black text-gray-900 mb-1">Upload New Photo</h3>
                        <p class="text-xs text-gray-400 font-bold mb-4">Recommended: Square JPEG or PNG, min 400x400px</p>
                        <input type="file" name="profile_image" onchange="previewImage(event)"
                            class="block w-full text-[10px] text-gray-400 font-black uppercase tracking-widest
                            file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 
                            file:text-[10px] file:font-black file:uppercase file:tracking-widest
                            file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                        @error('profile_image')
                            <p class="text-red-500 text-[10px] font-black mt-2 uppercase tracking-tighter">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Personal Info Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Account Information</h2>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold text-gray-700 transition @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-red-500 text-[10px] font-black mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none font-bold text-gray-700 transition @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-[10px] font-black mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Security Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <div>
                        <h2 class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Security & Password</h2>
                        <p class="text-[10px] text-gray-400 font-bold tracking-tight">Only fill if you wish to change your password.</p>
                    </div>
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div class="p-8 space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Current Password</label>
                        <input type="password" name="current_password"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 @error('current_password') border-red-500 @enderror">
                        @error('current_password') <p class="text-red-500 text-[10px] font-black mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">New Password</label>
                            <input type="password" name="new_password"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 @error('new_password') border-red-500 @enderror">
                            @error('new_password') <p class="text-red-500 text-[10px] font-black mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
                <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100 flex justify-end">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-10 py-3 rounded-xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition transform hover:-translate-y-1 active:scale-95">
                        Save Profile Changes
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-toast');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = "all 0.5s cubic-bezier(0.4, 0, 0.2, 1)";
                    alert.style.opacity = "0";
                    alert.style.transform = "translateX(100%)";
                    setTimeout(() => alert.remove(), 500);
                }, 4000);
            });
        });

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('preview');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection