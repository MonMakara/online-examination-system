<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Show form to create question
    public function createQuestions($examId)
    {

        $exam = Exam::withCount('questions')->findOrFail($examId);

        return view('teacher.exams.question', compact('exam'));
    }

    // Store questions
    public function storeQuestions(Request $request, $examId)
    {

        $request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_option' => ['required', 'string', 'in:a,b,c,d'],
        ]);

        $questions = new Question;

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
    // Show edit form
    public function editQuestions($id)
    {
        $question = Question::findOrFail($id);
        $exam = $question->exam; // Relationship assumed
        return view('teacher.exams.edit_question', compact('question', 'exam'));
    }

    // Update question
    public function updateQuestions(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $request->validate([
            'question' => ['required', 'string'],
            'option_a' => ['required', 'string'],
            'option_b' => ['required', 'string'],
            'option_c' => ['required', 'string'],
            'option_d' => ['required', 'string'],
            'correct_option' => ['required', 'string', 'in:a,b,c,d'],
        ]);

        $question->update([
            'question' => $request->question,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_option' => $request->correct_option,
        ]);

        return redirect()->route('teacher.exams.questions.create', $question->exam_id)
            ->with('success', 'Question updated successfully!');
    }

    // Delete question
    public function destroyQuestions($id)
    {
        $question = Question::findOrFail($id);
        $examId = $question->exam_id;
        $question->delete();

        return redirect()->route('teacher.exams.questions.create', $examId)
            ->with('success', 'Question deleted successfully.');
    }
}
