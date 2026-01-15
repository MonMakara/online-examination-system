<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Show form to create question 
    public function createQuestions($examId) {

        $exam = Exam::withCount('questions')->findOrFail($examId);

        return view('teacher.exams.question', compact('exam'));
    }


    // Store questions
    public function storeQuestions(Request $request, $examId) {

        $request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_option' => ['required', 'string', 'in:a,b,c,d'],
        ]);

        $questions = new Question();

        $questions->exam_id = $examId;
        $questions->question = $request->question;
        $questions->option_a = $request->option_a;
        $questions->option_b = $request->option_b;
        $questions->option_c = $request->option_c;
        $questions->option_d = $request->option_d;
        $questions->correct_option = $request->correct_option;

        $questions->save();

        return redirect()->back()->with('success', 'Question added successfully!');
    }



}
