<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\Result;
use Carbon\Carbon;

class StudentApiController extends Controller
{
    public function dashboard(Request $request)
    {
        $student = $request->user();
        $classIds = $student->enrolledClasses()->pluck('class_rooms.id');
        
        $pendingExamsCount = Exam::whereIn('class_id', $classIds)
            ->whereDoesntHave('results', function ($query) use ($student) {
                $query->where('user_id', $student->id);
            })->count();

        return response()->json([
            'status' => true,
            'stats' => [
                'enrolled_classes' => $classIds->count(),
                'pending_exams' => $pendingExamsCount
            ]
        ]);
    }

    public function classes(Request $request)
    {
        // Get classes the student is enrolled within
        $classes = $request->user()->classRooms()->with('teacher:id,name,profile_image')->get()->map(function($class) {
            return [
                'id' => $class->id,
                'name' => $class->name,
                'code' => $class->code,
                'logo_url' => $class->logo_url,
                'teacher' => [
                    'name' => $class->teacher->name ?? 'Unknown',
                    'profile_image_url' => $class->teacher->profile_image_url ?? null,
                ]
            ];
        });

        return response()->json([
            'status' => true,
            'classes' => $classes
        ]);
    }

    public function exams(Request $request)
    {
        $user = $request->user();
        $classIds = $user->classRooms()->pluck('class_rooms.id');

        // Fetch exams from these classes that student hasn't taken yet
        $exams = Exam::whereIn('class_id', $classIds)
            ->whereDoesntHave('results', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['classRoom:id,name,logo'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($exam) {
                return [
                    'id' => $exam->id,
                    'title' => $exam->title,
                    'class_name' => $exam->classRoom->name,
                    'class_logo_url' => $exam->classRoom->logo_url,
                    'start_date' => $exam->created_at->format('Y-m-d H:i'),
                ];
            });

        return response()->json([
            'status' => true,
            'exams' => $exams
        ]);
    }

    public function results(Request $request)
    {
        $results = Result::where('user_id', $request->user()->id)
            ->with(['exam', 'exam.classRoom'])
            ->get()
            ->map(function($result) {
                return [
                    'exam_title' => $result->exam->title,
                    'class_name' => $result->exam->classRoom->name,
                    'score' => $result->score,
                    'date' => $result->created_at->format('Y-m-d'),
                ];
            });

        return response()->json([
            'status' => true,
            'results' => $results
        ]);
    }

    public function joinClass(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:class_rooms,code',
        ]);

        $user = $request->user();

        if ($user->role !== 'student') {
            return response()->json(['status' => false, 'message' => 'Only students can join classes.'], 403);
        }

        $class = ClassRoom::where('code', $request->code)->first();

        if ($class->students()->where('users.id', $user->id)->exists()) {
             return response()->json(['status' => false, 'message' => 'You are already in this class.'], 409);
        }

        $class->students()->attach($user->id);

        return response()->json(['status' => true, 'message' => 'Successfully joined ' . $class->name]);
    }

    public function submitExam(Request $request, $id)
    {
        $user = $request->user();
        $exam = Exam::with('questions')->findOrFail($id);

        if ($exam->closed_at && now()->gt($exam->closed_at)) {
            return response()->json(['status' => false, 'message' => 'The submission window for this exam has closed.'], 403);
        }

        if (Result::where('exam_id', $id)->where('user_id', $user->id)->exists()) {
            return response()->json(['status' => false, 'message' => 'You have already submitted this exam.'], 409);
        }

        $request->validate(['answers' => 'required|array']);
        $studentAnswers = $request->input('answers');
        $correctCount = 0;
        $totalQuestions = $exam->questions->count();

        \DB::beginTransaction();
        try {
            foreach ($exam->questions as $question) {
                $selectedOption = $studentAnswers[$question->id] ?? null;
                
                \App\Models\StudentAnswer::create([
                    'user_id' => $user->id,
                    'exam_id' => $id,
                    'question_id' => $question->id,
                    'selected_option' => $selectedOption,
                ]);

                if ($selectedOption === $question->correct_option) {
                    $correctCount++;
                }
            }

            $finalScore = ($totalQuestions > 0) ? ($correctCount / $totalQuestions) * 100 : 0;

            Result::create([
                'user_id' => $user->id,
                'exam_id' => $id,
                'score' => round($finalScore),
            ]);

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Error submitting exam: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Exam submitted successfully!',
            'score' => round($finalScore)
        ]);
    }


    public function reviewExam(Request $request, $resultId)
    {
        $userId = $request->user()->id;

        $result = Result::where('id', $resultId)
            ->where('user_id', $userId)
            ->with(['exam.classRoom', 'exam.questions' => function($q) use ($userId) {
                 $q->with(['studentAnswers' => function($sa) use ($userId) {
                     $sa->where('user_id', $userId);
                 }]);
            }])
            ->firstOrFail();

        $examData = [
            'title' => $result->exam->title,
            'score' => $result->score,
            'questions' => $result->exam->questions->map(function($question) {
                return [
                    'id' => $question->id,
                    'text' => $question->question,
                    'options' => [
                        'a' => $question->option_a,
                        'b' => $question->option_b,
                        'c' => $question->option_c,
                        'd' => $question->option_d,
                    ],
                    'correct_option' => $question->correct_option,
                    'selected_option' => $question->studentAnswers->first()->selected_option ?? null,
                    'is_correct' => ($question->studentAnswers->first()->selected_option ?? null) === $question->correct_option
                ];
            })
        ];

        return response()->json(['status' => true, 'review' => $examData]);
    }
}
