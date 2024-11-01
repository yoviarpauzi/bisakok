<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Get all classrooms with student count.
     * 
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return Classroom::withCount('students')->get();
    }

    /**
     * Return all classrooms.
     * 
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function shows()
    {
        return Classroom::all();
    }

    /**
     * Create a new classroom.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'string', 'unique:classrooms,name'],
        ]);

        $classroom = new Classroom;
        $classroom->name = $credentials['name'];
        $classroom->save();

        return $classroom;
    }

    /**
     * Update classroom.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Delete classroom.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $credentials = $request->validate([
            'ids' => ['required', 'array'],
        ]);

        Classroom::destroy($credentials['ids']);
    }
}
