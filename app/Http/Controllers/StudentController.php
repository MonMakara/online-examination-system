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
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $student = Auth::user();

        $classes = $student->enrolledClasses()->with('teacher')->get();

        $classIds = $classes->pluck('id');

        $pendingExamsCount = Exam::whereIn('class_id', $classIds)
            ->whereDoesntHave('results', function ($query) use ($student) {
                $query->where('user_id', $student->id);
            })->count();

        return view('student.dashboard', compact('classes', 'pendingExamsCount'));
    }

    // Join class
    public function joinClass(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:class_rooms,code',
        ], [
            'code.required' => 'Please enter a class code.',
            'code.exists' => 'Invalid class code. Please check and try again.',
        ]);

        $user = auth()->user();

        // 1. Check if the user is actually a student
        if ($user->role !== 'student') {
            return back()->with('warning', 'Only students can join classes.');
        }

        $class = ClassRoom::where('code', $request->code)->first();

        // 2. Check if student is already in the class
        if ($class->students()->where('users.id', $user->id)->exists()) {
            return back()->with('warning', 'You are already in this class.');
        }

        // 3. Attach the student to the class
        $class->students()->attach($user->id);

        return redirect()->route('student.dashboard')->with('success', 'Successfully joined '.$class->name.'!');
    }

    public function profile()
    {
        return view('student.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Password Update Logic
        if ($request->filled('new_password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The provided current password does not match our records.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        // Image Upload Logic
        if ($request->hasFile('profile_image')) {
            // Delete old image if it exists
            if ($user->profile_image) {
                Storage::delete('public/'.$user->profile_image);
            }
            $path = $request->file('profile_image')->store('student_profiles', 'public');
            $user->profile_image = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;
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

        $exams = Exam::whereIn('class_id', $enrolledClassIds)
            ->whereDoesntHave('results', function ($query) use ($studentId) {
                $query->where('user_id', $studentId);
            })
            // Filter out exams that are strictly closed
            ->where(function ($query) use ($now) {
                $query->whereNull('closed_at')
                    ->orWhere('closed_at', '>', $now);
            })
            ->with(['classRoom.teacher']) // Links Exam -> ClassRoom -> Teacher
            ->withCount('questions')
            ->latest()
            ->get();

        return view('student.exams.active', compact('exams'));
    }

    public function startExam($id)
    {
        $studentId = auth()->id();

        // Fetch exam with questions and the teacher through classroom
        $exam = Exam::with(['questions', 'classRoom.teacher'])
            ->withCount('questions')
            ->findOrFail($id);

        // Security: Check if already submitted
        $alreadyTaken = Result::where('exam_id', $id)->where('user_id', $studentId)->exists();
        if ($alreadyTaken) {
            return redirect()->route('student.exams.index')->with('warning', 'You have already completed this exam.');
        }

        // Deadline Check: Is it hard closed?
        if ($exam->closed_at && now()->gt($exam->closed_at)) {
            return redirect()->route('student.exams.index')->with('warning', 'This exam is now closed.');
        }

        return view('student.exams.take', compact('exam'));
    }

    // Submit Exam Method
    public function submitExam(Request $request, $id)
    {
        $userId = auth()->id();
        $exam = Exam::with('questions')->findOrFail($id);
        $now = now();

        // 1. Hard Lock check
        if ($exam->closed_at && $now->gt($exam->closed_at)) {
            return redirect()->route('student.exams.index')
                ->with('warning', 'The submission window for this exam has closed.');
        }

        $request->validate(['answers' => 'required|array']);
        $studentAnswers = $request->input('answers');
        $correctCount = 0;
        $totalQuestions = $exam->questions->count();

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

        // 2. Save Result (Standard columns only)
        Result::create([
            'user_id' => $userId,
            'exam_id' => $id,
            'score' => round($finalScore),
        ]);

        // 3. Determine if they were late to show the correct message
        if ($exam->due_at && $now->gt($exam->due_at)) {
            return redirect()->route('student.exams.index')
                ->with('info', 'Exam submitted successfully, but it was marked as a LATE submission.');
        }

        return redirect()->route('student.exams.index')
            ->with('success', 'Exam submitted successfully! Score: '.round($finalScore).'%');
    }

    public function myResults()
    {
        $results = Result::where('user_id', auth()->id())
            ->with(['exam.classRoom.teacher'])
            ->latest()
            ->get();

        return view('student.results.index', compact('results'));
    }
}
