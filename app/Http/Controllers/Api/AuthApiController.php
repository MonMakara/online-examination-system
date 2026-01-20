<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\OTPMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AuthApiController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $otp = rand(100000, 999999);
        
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'student';
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->email_verified = false;

        DB::beginTransaction();
        try {
            $user->save();
            Mail::to($user->email)->send(new OTPMail($otp));
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Registration failed: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Registration successful. Please verify your email with the OTP sent.',
            'user_id' => $user->id
        ], 201);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'otp' => ['required', 'numeric', 'digits:6'],
        ]);

        $user = User::find($request->user_id);

        if ($user->otp == $request->otp && Carbon::now()->lessThanOrEqualTo($user->otp_expires_at)) {
            $user->update([
                'otp' => null,
                'otp_expires_at' => null,
                'email_verified' => true
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Email verified successfully',
                'token' => $token,
                'user' => $user
            ]);
        }

        return response()->json(['status' => false, 'message' => 'Invalid or expired OTP'], 400);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $user = User::where('email', $request->email)->first();

        if (!$user->password) {
            return response()->json(['status' => false, 'message' => 'This account uses social login.'], 400);
        }

        $otp = rand(100000, 999999);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        try {
            Mail::to($user->email)->send(new OTPMail($otp));
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Failed to send OTP'], 500);
        }

        return response()->json(['status' => true, 'message' => 'OTP sent to your email']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric|digits:6',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->otp == $request->otp && Carbon::now()->lessThanOrEqualTo($user->otp_expires_at)) {
            $user->update([
                'password' => Hash::make($request->password),
                'otp' => null,
                'otp_expires_at' => null
            ]);

            return response()->json(['status' => true, 'message' => 'Password reset successfully']);
        }

        return response()->json(['status' => false, 'message' => 'Invalid or expired OTP'], 400);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'profile_image_url' => $user->profile_image_url,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    public function user(Request $request)
    {
         return response()->json([
            'status' => true,
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role' => $request->user()->role,
                'profile_image_url' => $request->user()->profile_image_url,
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'profile_image' => 'nullable|image|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        if ($request->filled('new_password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                 return response()->json(['status' => false, 'message' => 'Current password incorrect'], 400);
            }
            $user->password = Hash::make($request->new_password);
        }

        if ($request->hasFile('profile_image')) {
            $folder = match($user->role) {
                'admin' => 'admin_profiles',
                'teacher' => 'teacher_profiles',
                default => 'student_profiles'
            };
            
            $imageService = app(\App\Services\ImageUploadService::class);
            $url = $imageService->upload($request->file('profile_image'), $folder);
            $user->profile_image = $url;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'status' => true, 
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_image_url' => $user->profile_image_url,
            ]
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        if ($user->password) {
            $request->validate([
                'password' => ['required'],
            ]);

            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['status' => false, 'message' => 'Current password incorrect'], 400);
            }
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json(['status' => true, 'message' => 'Account deleted successfully']);
    }
}
