<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use App\Course;

class CourseController extends Controller
{
    public $rules = [
        'name' => 'required|string|max:30',
        'duration' => 'required|integer|in:3,4,5'
    ];

    public function index() {
        $courses = Course::all();

        return view('admin.courses', compact('courses'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), $this->rules);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'output' => $validator->errors()->all()
            ]);
        }

        $course = new Course();
        $course->name = $request->input('name');
        $course->duration = $request->input('duration');

        if($course->save()) {
            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste dodali novi smjer!'],
            ]);
        }
        else return response()->json([
            'success' => false,
            'output' => ['Dogodila se neočekivana greška!']
        ]);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), $this->rules);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'output' => $validator->errors()->all()
            ]);
        }

        try {
            $course = Course::find($id);

            $course->name = $request->input('name');
            $course->duration = $request->input('duration');

            $course->save();

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste uredili ovaj smjer!'],
                'fields' => [
                    'name' => $course->name,
                    'duration' => $course->duration
                ]
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }
    }

    public function destroy($id) {
        try {
            $course = Course::find($id);

            $course->delete();

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste uklonili ovaj smjer!'],
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }
    }
}
