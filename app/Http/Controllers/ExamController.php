<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Get all exams with classroom and course info, also count questions.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Exam::with(['classroom', 'course'])->withCount('questions')->get();
    }

    /**
     * Retrieve a list of all exams.
     * 
     * @return \Illuminate\Http\Response
     */
    public function shows()
    {
        return Exam::all();
    }

    /**
     * Show a single exam.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Exam $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Exam $id)
    {
        // Find the exam by its id
        return Exam::find($id);
    }

    /**
     * Create a new exam
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'classrooms_id' => ['required', 'integer', 'exists:classrooms,id'],
            'courses_id' => ['required', 'integer', 'exists:courses,id'],
            'study_period' => ['required', 'string', 'max:10'],
            'session' => ['required', 'integer'],
            'type' => ['required', 'string', 'in:UTS,UAS,Quiz,Entrance'],
        ]);

        Exam::upsert(
            $credentials,
            ['classrooms_id', 'courses_id', 'study_period', 'session', 'type'],
            ['classrooms_id', 'courses_id', 'study_period', 'session', 'type']
        );
    }

    /**
     * Update an existing exam.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $credentials = $request->validate([
            'id' => ['required', 'integer', 'exists:exams,id'],
            'classrooms_id' => ['nullable', 'integer', 'exists:classrooms,id'],
            'courses_id' => ['nullable', 'integer', 'exists:courses,id'],
            'study_period' => ['required', 'string', 'max:10'],
            'session' => ['nullable', 'integer'],
            'type' => ['required', 'string', 'in:UTS,UAS,Quiz,Entrance'],
        ]);

        $exists = Exam::where('classrooms_id', $credentials['classrooms_id'])
            ->where('courses_id', $credentials['courses_id'])
            ->where('study_period', $credentials['study_period'])
            ->where('session', $credentials['session'])
            ->where('type', $credentials['type'])
            ->where('id', '!=', $credentials['id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['The combination of fields must be unique.']);
        } else {
            // If the combination is unique, update the exam
            $exam = Exam::find($credentials['id']);
            $exam->classrooms_id = $credentials['classrooms_id'];
            $exam->courses_id = $credentials['courses_id'];
            $exam->study_period = $credentials['study_period'];
            $exam->session = $credentials['session'];
            $exam->type = $credentials['type'];
            $exam->save();
        }
    }

    /**
     * Delete one or more exams.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $credentials = $request->validate([
            'ids' => ['required', 'array']
        ]);

        Exam::destroy($credentials['ids']);
    }
}
