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

        // Calculate Stats
        $activeExamsCount = $class->exams()
            ->where(function ($query) {
                $query->whereNull('closed_at')
                      ->orWhere('closed_at', '>', now());
            })
            ->count();

        $examIds = $exams->pluck('id');
        $averageScore = \App\Models\Result::whereIn('exam_id', $examIds)->avg('score');
        $classAverage = $averageScore ? round($averageScore, 1) . '%' : 'N/A';

        return view('teacher.classes.show', compact('class', 'students', 'exams', 'activeExamsCount', 'classAverage'));
    }
}
