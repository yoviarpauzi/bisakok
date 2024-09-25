<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function shows()
    {
        return Course::withCount('exams')->get();
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'string', 'unique:courses,name'],
        ]);

        $course = new Course;
        $course->name = $credentials['name'];
        $course->save();
    }

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

    public function delete(Request $request)
    {
        $credentials = $request->validate([
            'ids' => ['required', 'array'],
        ]);

        Course::destroy($credentials['ids']);
    }
}
