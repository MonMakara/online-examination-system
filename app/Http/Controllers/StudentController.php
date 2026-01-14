<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    //Dashboard
    public function dashboard() {
        return view('student.dashboard');
    }
}
