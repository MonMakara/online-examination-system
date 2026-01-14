{{-- @extends('layouts.student')

@section('title', 'Available Exams')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase">
            <tr>
                <th class="px-6 py-4 text-left">Exam Name</th>
                <th class="px-6 py-4 text-left">Classroom</th>
                <th class="px-6 py-4 text-left">Status</th>
                <th class="px-6 py-4 text-right">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($exams as $exam)
            <tr>
                <td class="px-6 py-4 font-bold text-gray-900">{{ $exam->title }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $exam->classroom->name }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('student.exams.take', $exam->id) }}" 
                       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition">
                        Start Exam
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection --}}