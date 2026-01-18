<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Show Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $otp = rand(100000, 999999);

        $user = new User;
        $user->name = request('name');
        $user->email = request('email');
        $user->password = Hash::make(request('password'));
        $user->role = 'student';

        // Database columns
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10); // Use plural 'addMinutes'
        $user->email_verified = false;

        $user->save();

        try {
            Mail::to($user->email)->send(new OTPMail($otp));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }

        session(['temp_user_id' => $user->id]);

        return redirect()->route('otp.verify')->with('success', 'Registered! Check email for OTP.');
    }

    // Show Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        // FIXED: 'passowrd' -> 'password'
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid email or password']);
        }

        $otp = rand(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10) // Use plural 'addMinutes'
        ]);

        try {
            Mail::to($user->email)->send(new OTPMail($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Error sending OTP.']);
        }

        session(['temp_user_id' => $user->id]);

        // FIXED: 'opt.verify' -> 'otp.verify'
        return redirect()->route('otp.verify');
    }

    // OTP Flow
    public function showOtpForm()
    {
        return view('auth.otp-verify');
    }

    public function verifyOtp(Request $request)
    {

        // FIXED: 'opt' -> 'otp'
        $request->validate([
            'otp' => ['required', 'numeric', 'digits:6'],
        ]);

        $userId = session('temp_user_id');

        if (!$userId) {
            return redirect()->route('show-login')->with('error', 'Session expired.');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('show-login')->with('error', 'User not found.');
        }

        // FIXED: 'opt' -> 'otp' AND 'opt_expires_at' -> 'otp_expires_at'
        if ($user->otp == $request->otp && Carbon::now()->lessThanOrEqualTo($user->otp_expires_at)) {

            // FIXED: Updating correct DB columns ('otp', not 'opt')
            $user->update([
                'otp' => null,
                'otp_expires_at' => null,
                'email_verified' => true
            ]);

            Auth::login($user);
            session()->forget('temp_user_id');
            $request->session()->regenerate();

            return match (Auth::user()->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'teacher' => redirect()->route('teacher.dashboard'),
                default => redirect()->route('student.dashboard'),
            };
        }

        return back()->withErrors(['otp' => 'Invalid or expired code.']);
    }

    // 1. Show Email Form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // 2. Send OTP to Email
    public function sendResetOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        // Generate OTP
        $otp = rand(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        try {
            Mail::to($user->email)->send(new OTPMail($otp));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send email.']);
        }

        // Store email in session to track who is resetting
        session(['reset_email' => $user->email]);

        return redirect()->route('password.otp.form')->with('success', 'OTP sent to your email.');
    }

    // 3. Show OTP Form
    public function showResetOtpForm()
    {
        return view('auth.reset-otp');
    }

    // 4. Verify OTP
    public function verifyResetOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric|digits:6']);

        $email = session('reset_email');
        if (!$email) return redirect()->route('password.request')->with('error', 'Session expired.');

        $user = User::where('email', $email)->first();

        if ($user && $user->otp == $request->otp && Carbon::now()->lessThanOrEqualTo($user->otp_expires_at)) {

            // Mark session as "verified" so they can access the next page
            session(['otp_verified_for_reset' => true]);

            // Clear OTP to prevent re-use
            $user->update(['otp' => null, 'otp_expires_at' => null]);

            return redirect()->route('password.reset.form');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    // 5. Show New Password Form
    public function showNewPasswordForm()
    {
        // Security Check: Don't let them here unless they verified OTP
        if (!session('otp_verified_for_reset')) {
            return redirect()->route('password.request');
        }

        return view('auth.new-password');
    }

    // 6. Update Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $email = session('reset_email');
        if (!$email) return redirect()->route('password.request');

        $user = User::where('email', $email)->first();

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Clean up session
        session()->forget(['reset_email', 'otp_verified_for_reset']);

        return redirect()->route('show-login')->with('success', 'Password reset successfully! Please login.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('show-login');
    }

    // 1. Send user to Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Handle the return
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('show-login')->with('error', 'Login failed');
        }

        // Check if user exists by Google ID or Email
        $user = User::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if ($user) {
            // Update Google ID if it was missing (e.g., they signed up with email before)
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->id]);
            }

            Auth::login($user);

            // Redirect based on role
            return $this->redirectBasedOnRole();
        } else {
            // Create new user
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'role' => 'student',
                'email_verified' => true,
                'password' => Hash::make(Str::random(24))
            ]);

            Auth::login($newUser);

            return $this->redirectBasedOnRole();
        }
    }

    private function redirectBasedOnRole()
    {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            default => redirect()->route('student.dashboard'),
        };
    }
}
