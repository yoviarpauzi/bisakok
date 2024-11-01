<?php

namespace App\Http\Controllers;

use App\Models\Choice;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Index page for the question resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exam  $exam
     * @return \Inertia\Response
     */
    public function index(Request $request, Exam $exam)
    {
        return inertia('pages/Question', [
            'test' => $exam->with('course', 'classroom')->first(),
        ]);
    }

    public function shows(Request $request, int $id)
    {
        return Question::with('choices')->where('exams_id', $id)->get();
    }

    public function create(Request $request, int $id)
    {
        return inertia('pages/AddQuestion', [
            'exams_id' => $id
        ]);
    }
}
