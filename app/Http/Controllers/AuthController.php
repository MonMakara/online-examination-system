<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class AuthController extends Controller
{
    // Show Register
    public function showRegister() {
        return view('auth.register');
    }

    // Register
    public function register(Request $request) {

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'min:5', 'confirmed'],
            ]);

            $user = new User();

            $user->name = request('name');
            $user->email = request('email');
            $user->password = Hash::make(request('password'));
            $user->role = 'student';

            $user->save();

            return redirect()->route('show-login')->with('success', 'Account created successfully');
    }

    // Show Login
    public function showLogin() {
        return view('auth.login');
    }

    // Login

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return match (Auth::user()->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'teacher' => redirect()->route('teacher.dashboard'),
                default => redirect()->route('student.dashboard'),
            };
        }

        return back()->withErrors([
            'email' => 'Invalid email or password',
        ]);

    }

    // Logout
    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }   


}
