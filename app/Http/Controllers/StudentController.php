<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Classroom;
use League\Csv\Reader;
use League\Csv\Statement;

class StudentController extends Controller
{
    public function index()
    {
        return User::with('classroom')->where('role', 'user')->get();
    }

    public function shows()
    {
        return User::where('role', 'user')->get();
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'classrooms_id' => ['nullable', 'integer', 'exists:classrooms,id'],
            'nisn' => ['required', 'string', 'max:15', 'unique:users,nisn'],
            'name' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8', 'same:password']
        ]);

        User::create([
            'classrooms_id' => $credentials['classrooms_id'],
            'nisn' => str_replace('-', '', $credentials['nisn']),
            'name' => $credentials['name'],
            'password' => bcrypt($credentials['password']),
        ]);
    }

    public function edit(Request $request)
    {
        $credentials = $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
            'classrooms_id' => ['nullable', 'integer', 'exists:classrooms,id'],
            'nisn' => ['required', 'string', 'max:15', 'unique:users,nisn,' . $request->id],
            'name' => ['required', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['nullable', 'string', 'min:8', 'same:password']
        ]);

        $user = User::find($credentials['id']);
        $user->classrooms_id = $credentials['classrooms_id'];
        $user->nisn = str_replace('-', '', $credentials['nisn']);
        $user->name = $credentials['name'];
        if (!empty($credentials['password'])) {
            $user->password = bcrypt($credentials['password']);
        }
        $user->save();
    }

    public function delete(Request $request)
    {
        $credentials = $request->validate([
            'ids' => ['required', 'array']
        ]);

        User::destroy($credentials['ids']);
    }

    public function uploadFile(Request $request)
    {
        $credentials = $request->validate([
            'file' => ['required', 'file', 'mimetypes:text/csv,text/plain,application/csv,application/vnd.ms-excel'],
        ]);

        $file = $request->file('file');
        Storage::disk('public')->put('uploads/csv/' . $file->getClientOriginalName(), File::get($file));

        $csv = Reader::createFromPath(storage_path('app/public/uploads/csv/' . $file->getClientOriginalName()), 'r');
        $csv->setHeaderOffset(0);
        $stmt = Statement::create();

        $users = $stmt->process($csv);
        $classrooms = Classroom::pluck('id', 'name')->toArray();

        $credentials = [];
        foreach ($users as $user) {
            $credentials[] = [
                'nisn' => $user['nisn'],
                'name' => $user['name'],
                'classrooms_id' => $classrooms[$user['classroom']] ?? null,
                'password' => bcrypt($user['password']),
            ];
        }

        Storage::disk('public')->delete('uploads/csv/' . $file->getClientOriginalName());

        User::upsert($credentials, ['nisn', 'name'], ['classrooms_id', 'password']);
    }

    public function downloadExample()
    {
        $file = storage_path('app/public/example/student-example.csv');

        if (file_exists($file)) {
            return response()->download($file);
        }

        return abort(404, 'File not found');
    }
}
