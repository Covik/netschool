<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\TheClass;
use App\Course;
use Validator;

class ClassController extends Controller
{
    private $rules = [
        'label' => 'required|string|max:3|alpha',
        'course' => 'required|integer|exists:courses,id',
        'year' => 'required|integer|before:+1 year|after:course,id'
    ];

    public function index() {
        $classes = TheClass::with('course')->get();
        $courses = Course::all();

        return view('admin.classes', compact('classes', 'courses'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), $this->rules);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'output' => $validator->errors()->all()
            ]);
        }

        if($this->sameClassExists($request)) {
            return response()->json([
                'success' => false,
                'output' => ['Razred već postoji!']
            ]);
        }

        $class = new TheClass();

        $class->label = $request->input('label');
        $class->course_id = $request->input('course');
        $class->year = $request->input('year');

        if($class->save()) {
            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste dodali novi razred!'],
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

        if($this->sameClassExists($request)) {
            return response()->json([
                'success' => false,
                'output' => ['Razred već postoji!']
            ]);
        }

        try {
            $class = TheClass::find($id);

            $class->label = $request->input('label');
            $class->course_id = $request->input('course');
            $class->year = $request->input('year');

            $class->save();

            $year = ($class->year-1).'. / '.$class->year.'.';

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste uredili ovaj razred!'],
                'fields' => [
                    'label' => $class->name,
                    'course' => $class->course->name,
                    'year' => $year
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
            $class = TheClass::find($id);

            $class->delete();

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste uklonili ovaj razred!'],
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }
    }


    private function sameClassExists(Request $request) {
        $sameClass = TheClass::where('label', '=', $request->input('label'))->where('course_id', '=', $request->input('course'))->where('year', '=', $request->input('year'));

        return $sameClass->exists();
    }
}
