<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\User;

class TeacherApiController extends Controller
{
    public function dashboard(Request $request)
    {
        $teacher = $request->user();
        $classes = \App\Models\ClassRoom::where('teacher_id', $teacher->id);
        
        $stats = [
            'classes_count' => $classes->count(),
            'students_count' => $classes->withCount('students')->get()->sum('students_count'),
            'exam_count' => \App\Models\Exam::where('teacher_id', $teacher->id)->count(),
        ];

        return response()->json(['status' => true, 'stats' => $stats]);
    }

    public function classes(Request $request)
    {
        $classes = $request->user()->managedClasses()->get()->map(function($class) {
            return [
                'id' => $class->id,
                'name' => $class->name,
                'code' => $class->code,
                'logo_url' => $class->logo_url,
                'student_count' => $class->students()->count(),
            ];
        });

        return response()->json([
            'status' => true,
            'classes' => $classes
        ]);
    }

    public function students(Request $request)
    {
        // Get all students enrolled in classes managed by this teacher
        $teacherId = $request->user()->id;

        $students = User::whereHas('enrolledClasses', function($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })
        ->select('id', 'name', 'email', 'profile_image')
        ->distinct()
        ->get()
        ->map(function($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'profile_image_url' => $student->profile_image_url,
            ];
        });

        return response()->json([
            'status' => true,
            'students' => $students
        ]);
    }

    // EXAM MANAGEMENT

    public function getExams(Request $request) 
    {
        $exams = \App\Models\Exam::where('teacher_id', $request->user()->id)
            ->with('classRoom:id,name')
            ->withCount('questions')
            ->latest()
            ->get();
        return response()->json(['status' => true, 'exams' => $exams]);
    }

    public function storeExam(Request $request)
    {
         $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'class_id' => ['required', 'exists:class_rooms,id'],
            'duration' => ['required', 'integer', 'min:1'],
            'due_at' => ['nullable', 'date'],
            'closed_at' => ['nullable', 'date', 'after_or_equal:due_at'],
        ]);

        $exam = new \App\Models\Exam;
        $exam->title = $request->title;
        $exam->class_id = $request->class_id;
        $exam->duration = $request->duration;
        $exam->due_at = $request->due_at;
        $exam->closed_at = $request->closed_at;
        $exam->teacher_id = $request->user()->id;
        $exam->save();

        return response()->json(['status' => true, 'message' => 'Exam created successfully', 'data' => $exam], 201);
    }

    public function updateExam(Request $request, $id)
    {
        $exam = \App\Models\Exam::where('teacher_id', $request->user()->id)->findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'class_id' => 'required|exists:class_rooms,id',
            'duration' => 'required|integer|min:1',
            'due_at' => 'nullable|date',
            'closed_at' => 'nullable|date|after_or_equal:due_at',
        ]);

        $exam->update($validated);

         return response()->json(['status' => true, 'message' => 'Exam updated successfully']);
    }

    public function destroyExam(Request $request, $id)
    {
        $exam = \App\Models\Exam::where('teacher_id', $request->user()->id)->findOrFail($id);
        $exam->delete();
        return response()->json(['status' => true, 'message' => 'Exam deleted successfully']);
    }

    // QUESTION MANAGEMENT

    public function storeQuestion(Request $request, $examId)
    {
        // verify ownership of exam
        $exam = \App\Models\Exam::where('teacher_id', $request->user()->id)->findOrFail($examId);

        $request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_option' => ['required', 'string', 'in:a,b,c,d'],
        ]);

        $question = new \App\Models\Question;
        $question->exam_id = $examId;
        $question->question = $request->question;
        $question->option_a = $request->option_a;
        $question->option_b = $request->option_b;
        $question->option_c = $request->option_c;
        $question->option_d = $request->option_d;
        $question->correct_option = $request->correct_option;
        $question->save();

        return response()->json(['status' => true, 'message' => 'Question added successfully', 'data' => $question], 201);
    }

    public function destroyQuestion(Request $request, $id)
    {
        $question = \App\Models\Question::findOrFail($id);
        // Security check: ensure the exam belongs to this teacher
        if ($question->exam->teacher_id !== $request->user()->id) {
             return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }
        $question->delete();
        return response()->json(['status' => true, 'message' => 'Question deleted successfully']);
    }


    // GRADE / RESULT VIEWS

    public function getClassGrades(Request $request, $classId)
    {
        $class = \App\Models\ClassRoom::where('id', $classId)
            ->where('teacher_id', $request->user()->id)
            ->firstOrFail();

        $exams = \App\Models\Exam::where('class_id', $classId)
            ->with(['results.student:id,name,email,profile_image'])
            ->latest()
            ->get()
            ->map(function($exam) {
                return [
                    'id' => $exam->id,
                    'title' => $exam->title,
                    'created_at' => $exam->created_at->format('Y-m-d'),
                    'results' => $exam->results->map(function($result) {
                        return [
                            'student_name' => $result->student->name,
                            'student_image' => $result->student->profile_image_url,
                            'score' => $result->score,
                            'submitted_at' => $result->created_at->format('Y-m-d H:i')
                        ];
                    })
                ];
            });

        return response()->json(['status' => true, 'class' => $class->only(['id', 'name']), 'exams' => $exams]);
    }
}
