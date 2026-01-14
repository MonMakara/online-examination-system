<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    //Dashboard
    public function dashboard() {
        return view('teacher.dashboard');
    }
}
