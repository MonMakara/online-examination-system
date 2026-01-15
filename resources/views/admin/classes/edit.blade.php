@extends('layouts.admin')

@section('title', 'Edit Classroom')

@section('content')
<div class="max-w-5xl">
    <div class="mb-6">
        <a href="{{ route('admin.classes.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 flex items-center font-medium">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Classrooms
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 py-4 lg:px-8 lg:py-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Edit Class: {{ $class->name }}</h2>
                <p class="text-sm text-gray-500">Update classroom details and management.</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Join Code</span>
                <p class="text-lg font-mono font-bold text-indigo-600">{{ $class->code }}</p>
            </div>
        </div>

        {{-- Added enctype for file support --}}
        <form action="{{ route('admin.classes.update', $class->id) }}" method="POST" enctype="multipart/form-data" class="p-4 lg:p-8 space-y-8">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <div class="space-y-6">
                    {{-- Class Name --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Class Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $class->name) }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none shadow-sm"
                            placeholder="e.g. Science 101">
                        @error('name')
                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Teacher Selection --}}
                    <div>
                        <label for="teacher_id" class="block text-sm font-semibold text-gray-700 mb-2">Assigned Teacher</label>
                        <div class="relative">
                            <select name="teacher_id" id="teacher_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 appearance-none bg-white transition outline-none shadow-sm">
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ $class->teacher_id == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Logo Management --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Classroom Logo</label>
                        <div class="flex items-start space-x-6">
                            {{-- Current Logo / Preview --}}
                            <div class="flex-shrink-0">
                                <div id="preview-container" class="h-28 w-28 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden">
                                    @if($class->logo)
                                        <img id="image-preview" src="{{ asset('storage/' . $class->logo) }}" class="h-full w-full object-cover">
                                    @else
                                        <span id="preview-placeholder" class="text-[10px] text-gray-400 font-bold uppercase text-center px-2">No Logo<br>Set</span>
                                        <img id="image-preview" class="hidden h-full w-full object-cover">
                                    @endif
                                </div>
                            </div>

                            {{-- Upload Input --}}
                            <div class="flex-grow">
                                <label class="block">
                                    <span class="sr-only">Choose logo</span>
                                    <input type="file" name="logo" onchange="previewImage(event)"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                                </label>
                                <p class="mt-2 text-[11px] text-gray-400">Upload a square image (PNG, JPG). Max 2MB. Leave blank to keep current logo.</p>
                                @error('logo')
                                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-6 flex items-center justify-end space-x-4 border-t border-gray-100">
                <a href="{{ route('admin.classes.index') }}" class="px-6 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-10 py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-md active:scale-95">
                    Update Classroom
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('image-preview');
            const placeholder = document.getElementById('preview-placeholder');
            
            output.src = reader.result;
            output.classList.remove('hidden');
            if(placeholder) placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection