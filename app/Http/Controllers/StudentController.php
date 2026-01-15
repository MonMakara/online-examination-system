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
    //Dashboard
    public function dashboard()
    {
        $student = Auth::user();

        $classes = $student->enrolledClasses()->with('teacher')->get();

        $classIds = $classes->pluck('id');
        
        $pendingExamsCount = Exam::whereIn('class_id', $classIds)
            ->whereDoesntHave('results', function($query) use ($student) {
                $query->where('user_id', $student->id);
            })->count();

        return view('student.dashboard', compact('classes', 'pendingExamsCount'));
    }

    // Join class
    public function joinClass(Request $request) {
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

        return redirect()->route('student.dashboard')->with('success', 'Successfully joined ' . $class->name . '!');
    }

    public function profile() {
        return view('student.profile', ['user' => auth()->user()]);

    }

    public function updateProfile(Request $request){
    $user = auth()->user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
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
            Storage::delete('public/' . $user->profile_image);
        }
        $path = $request->file('profile_image')->store('student_profiles', 'public');
        $user->profile_image = $path;
    }

    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return back()->with('success', 'Your profile has been updated successfully!');
}

    public function activeExams() {
    $studentId = auth()->id();

    // 1. Get IDs of classes the student is enrolled in
    $enrolledClassIds = DB::table('class_student')
        ->where('student_id', $studentId)
        ->pluck('class_id');

    // 2. Get exams for those classes that the student hasn't taken yet
    $exams = Exam::whereIn('class_id', $enrolledClassIds)
        ->whereDoesntHave('results', function($query) use ($studentId) {
            $query->where('user_id', $studentId);
        })
        ->with(['classRoom', 'questions'])
        ->withCount('questions')
        ->latest()
        ->get();

    return view('student.exams.active', compact('exams'));
}

public function startExam($id)
{
    // 1. Fetch exam with questions
    $exam = Exam::with('questions')->findOrFail($id);

    // 2. Security: Check if student already has a result for this exam
    $hasTaken = Result::where('user_id', auth()->id())
                      ->where('exam_id', $id)
                      ->exists();

    if ($hasTaken) {
        return redirect()->route('student.exams.index')
                         ->with('warning', 'You have already completed this exam.');
    }

    return view('student.exams.take', compact('exam'));
}


public function submitExam(Request $request, $id)
{
    $userId = auth()->id();
    // Load exam with questions to compare answers
    $exam = Exam::with('questions')->findOrFail($id);

    // 1. Validation
    $request->validate([
        'answers' => 'required|array',
    ]);

    $studentAnswers = $request->input('answers'); // Format: [question_id => 'a']
    $correctCount = 0;
    $totalQuestions = $exam->questions->count();

    // 2. Loop through questions to check correctness
    foreach ($exam->questions as $question) {
        $selectedOption = $studentAnswers[$question->id] ?? null;

        // Save individual answer for teacher review
        StudentAnswer::create([
            'user_id' => $userId,
            'exam_id' => $id,
            'question_id' => $question->id,
            'selected_option' => $selectedOption,
        ]);

        // Increment score if correct
        if ($selectedOption === $question->correct_option) {
            $correctCount++;
        }
    }

    // 3. Calculate percentage score
    $finalScore = ($totalQuestions > 0) ? ($correctCount / $totalQuestions) * 100 : 0;

    // 4. Save to Results table (Using the correct App\Models\Result)
    Result::create([
        'user_id' => $userId,
        'exam_id' => $id,
        'score' => round($finalScore),
    ]);

    return redirect()->route('student.exams.index')->with('success', 'Exam submitted successfully! Your score: ' . round($finalScore) . '%');
}

    
}
