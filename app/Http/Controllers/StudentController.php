<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\Result;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Services\ImageUploadService;

class StudentController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $student = Auth::user();

        $classes = $student->enrolledClasses()->with('teacher')->paginate(10);

        $classIds = collect($classes->items())->pluck('id');

        $pendingExamsCount = Exam::whereIn('class_id', $classIds)
            ->whereDoesntHave('results', function ($query) use ($student) {
                $query->where('user_id', $student->id);
            })
            ->where(function ($query) {
                $query->whereNull('closed_at')
                    ->orWhere('closed_at', '>', now());
            })->count();

        return view('student.dashboard', compact('classes', 'pendingExamsCount'));
    }

    // Join a class using a code
    public function joinClass(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'exists:class_rooms,code'],
        ], [
            'code.required' => 'Please enter a class code.',
            'code.exists' => 'Invalid class code. Please check and try again.',
        ]);

        $user = auth()->user();

        if ($user->role !== 'student') {
            return back()->with('warning', 'Only students can join classes.');
        }

        $class = ClassRoom::where('code', $request->code)->first();

        // Check if student is already in the class
        if ($class->students()->where('users.id', $user->id)->exists()) {
            return back()->with('warning', 'You are already in this class.');
        }

        // Add the student to the class
        $class->students()->attach($user->id);

        return redirect()->route('student.dashboard')->with('success', 'Successfully joined ' . $class->name . '!');
    }

    public function showClass($id)
    {
        $student = auth()->user();

        // Check if student is enrolled in this class
        $class = $student->enrolledClasses()
            ->with(['teacher', 'exams' => function ($query) use ($student) {
                $query->latest()->with(['results' => function ($q) use ($student) {
                    $q->where('user_id', $student->id);
                }]);
            }])
            ->findOrFail($id);

        return view('student.classes.show', compact('class'));
    }

    public function profile()
    {
        return view('student.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request, ImageUploadService $imageService)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:6', 'confirmed'],
        ]);

        if ($request->filled('new_password')) {

            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The provided current password does not match our records.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        if ($request->hasFile('profile_image')) {
            $url = $imageService->upload($request->file('profile_image'), 'student_profiles');
            $user->profile_image = $url;
        }

        $user->name = $request->name;

        $user->save();

        return back()->with('success', 'Your profile has been updated successfully!');
    }

    // Active Exams Method
    public function activeExams()
    {
        $studentId = auth()->id();
        $now = now();

        $enrolledClassIds = DB::table('class_student')
            ->where('student_id', $studentId)
            ->pluck('class_id');

        $baseQuery = Exam::whereIn('class_id', $enrolledClassIds)
            ->whereDoesntHave('results', function ($query) use ($studentId) {
                $query->where('user_id', $studentId);
            })
            ->with(['classRoom.teacher'])
            ->withCount('questions');

        $activeExams = (clone $baseQuery)
            ->where(function ($query) use ($now) {
                $query->whereNull('closed_at')
                    ->orWhere('closed_at', '>', $now);
            })
            ->latest()
            ->paginate(10, ['*'], 'active_page');

        $missedExams = (clone $baseQuery)
            ->where('closed_at', '<=', $now)
            ->latest()
            ->paginate(10, ['*'], 'missed_page');

        return view('student.exams.active', compact('activeExams', 'missedExams'));
    }

    public function startExam($id)
    {
        $studentId = auth()->id();

        $exam = Exam::with(['questions', 'classRoom.teacher'])
            ->withCount('questions')
            ->findOrFail($id);

        $alreadyTaken = Result::where('exam_id', $id)->where('user_id', $studentId)->exists();
        if ($alreadyTaken) {
            return redirect()->route('student.exams.index')->with('warning', 'You have already completed this exam.');
        }

        if ($exam->closed_at && now()->gt($exam->closed_at)) {
            return redirect()->route('student.exams.index')->with('warning', 'This exam is now closed.');
        }

        $examClosedAt = $exam->closed_at
            ? $exam->closed_at->toIso8601String()
            : null;

        $examDueAt = $exam->due_at
            ? $exam->due_at->toIso8601String()
            : null;

        return view('student.exams.take', compact(
            'exam',
            'examClosedAt',
            'examDueAt'
        ));
    }

    public function submitExam(Request $request, $id)
    {
        $userId = auth()->id();
        $exam = Exam::with('questions')->findOrFail($id);
        $now = now();

        if ($exam->closed_at && $now->gt($exam->closed_at)) {
            return redirect()->route('student.exams.index')
                ->with('warning', 'The submission window for this exam has closed.');
        }

        $alreadyTaken = Result::where('exam_id', $id)->where('user_id', $userId)->exists();
        if ($alreadyTaken) {
            return redirect()->route('student.exams.index')->with('warning', 'You have already submitted this exam.');
        }

        $request->validate(['answers' => ['required', 'array']]);
        $studentAnswers = $request->input('answers');
        $correctCount = 0;
        $totalQuestions = $exam->questions->count();

        DB::beginTransaction();
        try {
            foreach ($exam->questions as $question) {
                $selectedOption = $studentAnswers[$question->id] ?? null;

                StudentAnswer::create([
                    'user_id' => $userId,
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
                'user_id' => $userId,
                'exam_id' => $id,
                'score' => round($finalScore),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred during submission: ' . $e->getMessage());
        }

        if ($exam->due_at && $now->gt($exam->due_at)) {
            return redirect()->route('student.exams.index')
                ->with('info', 'Exam submitted successfully, but it was marked as a LATE submission.');
        }

        return redirect()->route('student.exams.index')
            ->with('success', 'Exam submitted successfully! Score: ' . round($finalScore) . '%');
    }

    public function myResults()
    {
        $results = Result::where('user_id', auth()->id())
            ->with(['exam.classRoom.teacher'])
            ->latest()
            ->paginate(10);

        return view('student.results.index', compact('results'));
    }

    public function reviewExam($id)
    {
        $userId = auth()->id();

        $result = Result::where('id', $id)
            ->where('user_id', $userId)
            ->with('exam.classRoom')
            ->firstOrFail();

        $exam = Exam::with(['questions' => function ($q) use ($userId) {
            $q->with(['studentAnswers' => function ($sa) use ($userId) {
                $sa->where('user_id', $userId);
            }]);
        }])->findOrFail($result->exam_id);

        return view('student.results.review', compact('exam', 'result'));
    }
}
