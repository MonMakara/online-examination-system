<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClassRoom;

class AdminApiController extends Controller
{
    public function dashboard()
    {
        $teachers = User::where('role', 'teacher')->count();
        $studentCount = User::where('role', 'student')->count();
        $classes = ClassRoom::count();

        return response()->json([
            'status' => true,
            'stats' => [
                'teachers' => $teachers,
                'students' => $studentCount,
                'classes' => $classes,
            ]
        ]);
    }

    public function teachers(Request $request)
    {
        $query = User::where('role', 'teacher');

        if ($request->has('search') && $request->search != '') {
             $search = $request->search;
             $query->where(function ($q) use ($search) {
                 $q->where('name', 'like', "%{$search}%")
                     ->orWhere('email', 'like', "%{$search}%");
             });
        }

        $teachers = $query->select('id', 'name', 'email', 'profile_image')
            ->get()
            ->map(function($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'email' => $teacher->email,
                    'profile_image_url' => $teacher->profile_image_url,
                ];
            });

        return response()->json([
            'status' => true,
            'teachers' => $teachers
        ]);
    }

    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $teacher = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'teacher',
        ]);

        return response()->json(['status' => true, 'message' => 'Teacher created successfully', 'data' => $teacher], 201);
    }

    public function updateTeacher(Request $request, $id)
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
            $teacher->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);
        }

        return response()->json(['status' => true, 'message' => 'Teacher updated successfully']);
    }

    public function destroyTeacher($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $teacher->delete();
        return response()->json(['status' => true, 'message' => 'Teacher deleted successfully']);
    }

    public function classes(Request $request)
    {
        $query = ClassRoom::with('teacher');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $classes = $query->get()
            ->map(function($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'code' => $class->code,
                    'logo_url' => $class->logo_url,
                    'teacher_name' => $class->teacher->name ?? 'Unassigned',
                ];
            });

        return response()->json([
            'status' => true,
            'classes' => $classes
        ]);
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'logo' => 'nullable|image|max:2048',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            // Using the Service would be ideal but for API simplicity/di issues we might instantiate or just use Cloudinary facade directly if easier, 
            // but let's try to do it right. We'll resolve the service.
            $imageService = app(\App\Services\ImageUploadService::class);
            $logoPath = $imageService->upload($request->file('logo'), 'class_logos');
        }

        $class = ClassRoom::create([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'logo' => $logoPath,
            'code' => strtoupper(\Illuminate\Support\Str::random(6)),
        ]);

        return response()->json(['status' => true, 'message' => 'Class created successfully', 'data' => $class], 201);
    }

    public function updateClass(Request $request, $id)
    {
        $class = ClassRoom::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:users,id',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $imageService = app(\App\Services\ImageUploadService::class);
            $logoPath = $imageService->upload($request->file('logo'), 'class_logos');
            $class->logo = $logoPath;
        }

        $class->update([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'logo' => $class->logo,
        ]);

        return response()->json(['status' => true, 'message' => 'Class updated successfully']);
    }

    public function destroyClass($id)
    {
        $class = ClassRoom::findOrFail($id);
        $class->delete();
        return response()->json(['status' => true, 'message' => 'Class deleted successfully']);
    }
}
