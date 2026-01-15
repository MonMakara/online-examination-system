<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    //Dashboard
    public function dashboard() {
        $teacher = auth()->user();

        // Change ->get() to ->paginate(5)
        $classes = ClassRoom::where('teacher_id', $teacher->id)
                    ->withCount(['students', 'exams'])
                    ->paginate(5);

        // We still calculate totals the same way
        $totalStudent = ClassRoom::where('teacher_id', $teacher->id)
                        ->withCount('students')
                        ->get()
                        ->sum('students_count');

        $examCount = Exam::whereIn('class_id', $classes->pluck('id'))->count();

        return view('teacher.dashboard', compact('classes', 'totalStudent', 'examCount'));
    }

    /* Classes management */

    // Show page classes
    public function indexClasses() {
        $teacher = auth()->user();

        $classes = ClassRoom::where('teacher_id', $teacher->id)->withCount('students')->get();

        return view('teacher.classes.index', compact('classes'));
    }


    public function profile() {
        return view('teacher.profile', ['user' => auth()->user()]);
    }

    /* Profile management */

    // Update profile
    public function updateProfile(Request $request) {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Handle Password Update
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        // Handle Image Upload
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::delete('public/' . $user->profile_image);
            }
            $path = $request->file('profile_image')->store('profile_photos', 'public');
            $user->profile_image = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    // Show page grade
    public function showGrades() {
        $exams = Exam::where('teacher_id', auth()->id())->with(['results.student', 'classRomm'])->latest()->get();
        return view('teacher.grades.index', compact('exams'));
    }
}
