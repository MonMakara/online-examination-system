<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Services\ImageUploadService;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $teachers = User::where('role', 'teacher')->get();
        $classes = ClassRoom::with('teacher')->get();
        $studentCount = User::where('role', 'student')->count();

        return view('admin.dashboard', compact('teachers', 'classes', 'studentCount'));
    }

    /* Teachers management */

    // Show teachers page
    public function indexTeachers(Request $request)
    {
        $query = User::where('role', 'teacher')->with('managedClasses');

        // If search term exists, filter by name or email
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $teachers = $query->paginate(10);

        // This ensures your pagination links keep the search term attached
        $teachers->appends(['search' => $request->search]);

        return view('admin.teachers.index', compact('teachers'));
    }

    // Show form to create teacher
    public function createTeachers()
    {
        return view('admin.teachers.create');
    }

    // Store teacher
    public function storeTeachers(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher account created successfully!');
    }

    // Show form to update teacher
    public function editTeachers($id)
    {

        $teacher = User::where('role', 'teacher')->findOrFail($id);

        return view('admin.teachers.edit', compact('teacher'));
    }

    // Update teacher
    public function updateTeachers(Request $request, $id)
    {

        $teacher = User::where('role', 'teacher')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);

        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $teacher->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.teachers.index')->with('info', 'Teacher information has been updated.');
    }

    // Delete teacher
    public function destroyTeachers($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('warning', 'Teacher has been removed from the system.');
    }

    /* Classes management */

    // Show classes page
    public function indexClasses(Request $request)
    {

        $query = ClassRoom::with('teacher')->withCount('students');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        // withQueryString() keeps the ?search=... in the pagination links
        $classes = $query->paginate(10)->withQueryString();

        return view('admin.classes.index', compact('classes'));
    }

    // Show form to create class
    public function createClasses()
    {

        $teachers = User::where('role', 'teacher')->get();

        return view('admin.classes.create', compact('teachers'));
    }

    // Store class
    public function storeClasses(Request $request, ImageUploadService $imageService)
    {
        // 1. Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', 
        ]);

        $logoPath = null;

        // 2. Logic to store the file
        if ($request->hasFile('logo')) {
            // Upload to Cloudinary using Service
            $logoPath = $imageService->upload($request->file('logo'), 'class_logos');
        }

        // 3. Create the class with the logo path
        ClassRoom::create([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'logo' => $logoPath, 
            'code' => strtoupper(Str::random(6)),
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully!');
    }

    // Show form to edit class
    public function editClasses($id)
    {
        $class = ClassRoom::findOrFail($id);
        $teachers = User::where('role', 'teacher')->get();

        return view('admin.classes.edit', compact('class', 'teachers'));
    }

    // Update class
    public function updateClasses(Request $request, $id, ImageUploadService $imageService)
    {
        $class = ClassRoom::findOrFail($id);

        // 1. Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // Added logo validation
        ]);

        // 2. Handle Logo Upload
        if ($request->hasFile('logo')) {
            // Upload new logo to Cloudinary
            $logoPath = $imageService->upload($request->file('logo'), 'class_logos');
            $class->logo = $logoPath;
        }

        // 3. Update the record
        $class->update([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'logo' => $class->logo, 
        ]);

        return redirect()->route('admin.classes.index')->with('info', 'Class updated successfully');
    }

    // Destroy class
    public function destroyClasses($id)
    {
        $class = ClassRoom::findOrFail($id);
        $class->delete();

        return redirect()->route('admin.classes.index')->with('warning', 'Class deleted successfully');
    }

    // Show setting
    public function profile()
    {

        return view('admin.profile', ['user' => auth()->user()]);
    }

    // Update profile
    public function updateProfile(Request $request, ImageUploadService $imageService)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => ['nullable', 'confirmed', 'min:6'],
        ]);

        if ($request->hasFile('profile_image')) {
            // Upload to Cloudinary
            $path = $imageService->upload($request->file('profile_image'), 'admin_profiles');
            $user->profile_image = $path;
        }

        if ($request->filled('new_password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Your current password does not match our records.']);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}
