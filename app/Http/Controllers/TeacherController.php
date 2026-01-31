<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\ImageUploadService;

class TeacherController extends Controller
{
    // Dashboard page
    public function dashboard()
    {
        $teacher = auth()->user();

        $classes = ClassRoom::where('teacher_id', $teacher->id)
            ->with(['students'])
            ->withCount(['students', 'exams'])
            ->paginate(10);

        $totalStudent = ClassRoom::where('teacher_id', $teacher->id)
            ->withCount('students')
            ->get()
            ->sum('students_count');

        $examCount = Exam::whereIn('class_id', ClassRoom::where('teacher_id', $teacher->id)->pluck('id'))->count();

        return view('teacher.dashboard', compact('classes', 'totalStudent', 'examCount'));
    }

    /* Classes management */

    // Show page classes
    public function indexClasses()
    {
        $teacher = auth()->user();

        $classes = ClassRoom::where('teacher_id', $teacher->id)->withCount('students')->paginate(10);

        return view('teacher.classes.index', compact('classes'));
    }

    
    /* Profile management */

    public function profile()
    {
        return view('teacher.profile', ['user' => auth()->user()]);
    }
    // Update profile
    public function updateProfile(Request $request, ImageUploadService $imageService)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'min:6', 'confirmed'],
        ]);

        if ($request->filled('new_password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        if ($request->hasFile('profile_image')) {
            $url = $imageService->upload($request->file('profile_image'), 'teacher_profiles');
            $user->profile_image = $url;
        }

        $user->name = $request->name;

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    // Show page grade
    public function gradesIndex()
    {
        $classes = ClassRoom::where('teacher_id', auth()->id())
            ->withCount(['students', 'exams'])
            ->paginate(10);

        return view('teacher.grades.select_class', compact('classes'));
    }


    public function showClassGrades($id)
    {
        $class = ClassRoom::where('id', $id)
            ->where('teacher_id', auth()->id())
            ->firstOrFail();

        $exams = Exam::where('class_id', $id)
            ->with(['classRoom'])
            ->withCount('results') // count results instead of loading them
            ->withAvg('results', 'score') // Get average directly via query
            ->latest()
            ->paginate(10);

        return view('teacher.grades.index', compact('exams', 'class'));
    }

    public function showExamGrades($id)
    {
        $exam = Exam::with(['classRoom', 'results.student'])
            ->withCount('questions')
            ->findOrFail($id);
            
        // Check ownership/permission (optional but recommended)
        if($exam->classRoom->teacher_id !== auth()->id()) {
            abort(403);
        }

        return view('teacher.grades.show', compact('exam'));
    }
}
