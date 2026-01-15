<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;

class ClassroomController extends Controller
{
    // Show page class

    public function showManageClass($id)
    {
        $class = ClassRoom::where('teacher_id', auth()->id())->findOrFail($id);

        $students = $class->students()->orderBy('name')->get();

        $exams = $class->exams()->latest()->get();

        return view('teacher.classes.show', compact('class', 'students', 'exams'));
    }
}
