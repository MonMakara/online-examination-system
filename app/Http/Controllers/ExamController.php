<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    // Page exam for teacher
    public function index()
    {
        $exams = Exam::whereHas('classRoom', function ($query) {
            $query->where('teacher_id', auth()->id());
        })
            ->with('classRoom')
            ->withCount('questions')
            ->latest()
            ->paginate(10);

        return view('teacher.exams.index', compact('exams'));
    }

    // Show page to create exam
    public function createExams()
    {
        $classes = ClassRoom::where('teacher_id', auth()->id())->get();

        return view('teacher.exams.create', compact('classes'));
    }

    // Store exam
    public function storeExams(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'class_id' => ['required', 'exists:class_rooms,id'],
            'duration' => ['required', 'integer', 'min:1'],
            'due_at' => ['nullable', 'date'],
            'closed_at' => ['nullable', 'date', 'after_or_equal:due_at'],
        ]);

        $exam = new Exam;
        $exam->title = $request->title;
        $exam->class_id = $request->class_id;
        $exam->duration = $request->duration;
        $exam->due_at = $request->due_at;
        $exam->closed_at = $request->closed_at;
        $exam->teacher_id = auth()->id();
        $exam->save();

        return redirect()->route('teacher.exams.questions.create', $exam->id)
            ->with('success', 'Exam created! Now add some questions.');
    }

    // Show form to update exam
    public function editExams($id)
    {
        $exam = Exam::where('teacher_id', auth()->id())->findOrFail($id);
        $classes = ClassRoom::where('teacher_id', auth()->id())->get();

        return view('teacher.exams.edit', compact('exam', 'classes'));
    }

    // Update exam
    public function updateExams(Request $request, $id)
    {
        $exam = Exam::where('teacher_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'class_id' => ['required', 'exists:class_rooms,id'],
            'duration' => ['required', 'integer', 'min:1'],
            'due_at' => ['nullable', 'date'],
            'closed_at' => ['nullable', 'date', 'after_or_equal:due_at'],
        ]);

        $exam->update($validated);

        return redirect()->route('teacher.exams.index')
            ->with('success', 'Exam updated successfully!');
    }

    // Delete the exam
    public function destroyExams($id)
    {
        $exam = Exam::where('teacher_id', auth()->id())->findOrFail($id);

        $exam->delete();

        return redirect()->route('teacher.exams.index')->with('success', 'Exam deleted successfully.');
    }
}
