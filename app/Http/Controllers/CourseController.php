<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Show a list of all courses.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Course::withCount('exams')->get();
    }

    /**
     * Retrieve a list of all courses.
     *
     * @return \Illuminate\Http\Response
     */
    public function shows()
    {
        return Course::all();
    }

    /**
     * Create a new course
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'string', 'unique:courses,name'],
        ]);

        $course = new Course;
        $course->name = $credentials['name'];
        $course->save();
    }

    /**
     * Update a course
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $credentials = $request->validate([
            'id' => ['required', 'integer'],
            'name' => ['required', 'string', 'unique:courses,name']
        ]);

        $course = Course::find($credentials['id']);
        $course->name = $credentials['name'];
        $course->save();
    }

    /**
     * Delete courses
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $credentials = $request->validate([
            'ids' => ['required', 'array'],
        ]);

        Course::destroy($credentials['ids']);
    }
}
