<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    // 1. REGISTER
    public function showRegister()
    {
        return view('auth.register');
    }

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
        
        // OTP Setup
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
            Log::error('Registration Error: ' . $e->getMessage(), [
                'exception' => $e,
                'user_email' => $user->email
            ]);
            return back()->with('error', 'Registration failed: ' . $e->getMessage());
        }

        session(['temp_user_id' => $user->id]);

        return redirect()->route('otp.verify')->with('success', 'Registered! Check email for OTP.');
    }

    // 2. LOGIN
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
             return back()->withErrors(['email' => 'This email is not registered in our system.']);
        }

        if (!$user->password || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Incorrect password.']);
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        try {
            Mail::to($user->email)->send(new OTPMail($otp));
        } catch (\Exception $e) {
            Log::error('Mail Error (Login): ' . $e->getMessage(), [
                'exception' => $e,
                'user_email' => $user->email
            ]);
            return back()->withErrors(['email' => 'Error sending OTP: ' . $e->getMessage()]);
        }

        session(['temp_user_id' => $user->id]);

        return redirect()->route('otp.verify');
    }

    // 3. OTP VERIFICATION

    // Show OTP Form
    public function showOtpForm() {
        return view('auth.otp-verify');
    }

    // Verify OTP
    public function verifyOtp(Request $request) {
        
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

        if ($user->otp == $request->otp && Carbon::now()->lessThanOrEqualTo($user->otp_expires_at)) {
            $user->update([
                'otp' => null,
                'otp_expires_at' => null,
                'email_verified' => true
            ]);

            Auth::login($user);
            session()->forget('temp_user_id');
            $request->session()->regenerate();

            return $this->redirectBasedOnRole();
        }

        return back()->withErrors(['otp' => 'Invalid or expired code.']);
    }

    // 4. GOOGLE LOGIN
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('show-login')->with('error', 'Google Login failed. Try again.');
        }

        $user = User::where('google_id', $googleUser->id)
                    ->orWhere('email', $googleUser->email)
                    ->first();
        if ($user) {
            // Update Google ID if user registered with email before but now uses Google
            if (!$user->google_id) {
                $user->update(['google_id' => $googleUser->id]);
            }
            Auth::login($user);
            return $this->redirectBasedOnRole();
        } else {
            // Create new user (No Password)
            $newUser = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'role' => 'student',
                'email_verified' => true,
                'password' => null
            ]);

            Auth::login($newUser);
            return $this->redirectBasedOnRole();
        }
    }

    // 5. DELETE ACCOUN

    // Show delete Account
    public function showDeleteAccount()
    {
        return view('auth.delete-account');
    }

    // Delete Account
    public function destroyAccount(Request $request)
    {
        $user = Auth::user();

        if ($user->password) {
            $request->validate([
                'password' => ['required', 'current_password'],
            ]);
        }

        Auth::logout();

        if ($user->delete()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('show-login')->with('success', 'Your account has been permanently deleted.');
        }

        return back()->with('error', 'Error deleting account.');
    }

    // 6. FORGOT PASSWORD FLOW
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => "We can't find a user with that e-mail address."]);
        }

        if (!$user->password) {
             return back()->withErrors(['email' => 'This account uses Google Login. Please sign in with Google.']);
        }

        $otp = rand(100000, 999999);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        try {
            Mail::to($user->email)->send(new OTPMail($otp));
        } catch (\Exception $e) {
            Log::error('Mail Error (Reset Password): ' . $e->getMessage(), [
                'exception' => $e,
                'user_email' => $user->email
            ]);
            return back()->withErrors(['email' => 'Failed to send email: ' . $e->getMessage()]);
        }

        session(['reset_email' => $user->email]);
        return redirect()->route('password.otp.form')->with('success', 'OTP sent to your email.');
    }

    public function showResetOtpForm()
    {
        return view('auth.reset-otp'); 
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate(['otp' => ['required', 'numeric', 'digits:6']]);
        $email = session('reset_email');
        if (!$email) return redirect()->route('password.request')->with('error', 'Session expired.');

        $user = User::where('email', $email)->first();

        if ($user && $user->otp == $request->otp && Carbon::now()->lessThanOrEqualTo($user->otp_expires_at)) {
            session(['otp_verified_for_reset' => true]);
            $user->update(['otp' => null, 'otp_expires_at' => null]);
            return redirect()->route('password.reset.form');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    public function showNewPasswordForm()
    {
        if (!session('otp_verified_for_reset')) return redirect()->route('password.request');
        return view('auth.new-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['password' => ['required', 'min:6', 'confirmed']]);
        $email = session('reset_email');
        if (!$email) return redirect()->route('password.request');

        $user = User::where('email', $email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        session()->forget(['reset_email', 'otp_verified_for_reset']);
        return redirect()->route('show-login')->with('success', 'Password reset successfully! Please login.');
    }

    // 7. LOGOUT & HELPER
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('show-login');
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