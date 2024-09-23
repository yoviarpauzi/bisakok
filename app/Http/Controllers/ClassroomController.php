<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function shows()
    {
        return Classroom::withCount('students')->get();
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'string', 'unique:classrooms,name'],
        ]);

        $classroom = new Classroom;
        $classroom->name = $credentials['name'];
        $classroom->save();
    }

    public function edit(Request $request)
    {
        $credentials = $request->validate([
            'id' => ['required', 'integer'],
            'name' => ['required', 'string', 'unique:classrooms,name']
        ]);

        $classroom = Classroom::find($credentials['id']);
        $classroom->name = $credentials['name'];
        $classroom->save();
    }

    public function delete(Request $request)
    {
        $credentials = $request->validate([
            'ids' => ['required', 'array'],
        ]);

        Classroom::destroy($credentials['ids']);
    }
}
