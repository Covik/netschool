<?php

namespace App\Http\Controllers;

use App\ProfessorSubject;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\TheClass;
use App\Course;
use Validator;
use App\User;

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

        return view('admin.classes.index', compact('classes', 'courses'));
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

    public function single($id) {
        $class = TheClass::find($id);

        $professors = User::professors()->get();

        $allSubjects = $class->course->subjects()->where('course_year', '=', $class->getClassNumber())->get();

        $exceptSubjects = [];

        if(!empty($class->ps)) {
            foreach ($class->ps as $ps) {
                $exceptSubjects[] = $ps->subject->id;
            }
        }

        $subjects = $allSubjects->filter(function($subject) use ($exceptSubjects) {
            return !in_array($subject->id, $exceptSubjects);
        });

        return view('admin.classes.single', compact('class', 'subjects', 'professors'));
    }

    public function storePS(Request $request, $id) {
        $class = TheClass::find($id);

        $validator = Validator::make($request->all(), [
            'subject' => 'required|integer|exists:subjects,id',
            'professor' => 'required|integer|exists:users,id,role,'.config('roles.professor')
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'output' => $validator->errors()->all()
            ]);
        }

        try {

            $subject = $request->input('subject');
            $professor = $request->input('professor');

            $ps = ProfessorSubject::firstOrCreate([
                'professor_id' => $professor,
                'subject_id' => $subject
            ]);

            $class->ps()->attach($ps->id);

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste dodali predmet i nastavnika!'],
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }
    }

    public function updatePS(Request $request, $id, $psid) {
        $class = TheClass::find($id);

        $validator = Validator::make(['nastavnik' => $request->input('professor'), 'predmet' => $psid], [
            'predmet' => 'required|integer|exists:professor_subjects,id',
            'nastavnik' => 'required|integer|exists:users,id,role,'.config('roles.professor')
        ]);

        if($validator->fails()) {
            $errors = [];

            if($validator->errors()->get('predmet')) $errors[] = 'Dogodila se neočekivana greška';
            else $errors = $validator->errors()->all();

            return response()->json([
                'success' => false,
                'output' => $errors
            ]);
        }

        try {
            $psOld = ProfessorSubject::find($psid);

            $subject = $psOld->subject->id;
            $professor = $request->input('professor');

            $ps = ProfessorSubject::firstOrCreate([
                'professor_id' => $professor,
                'subject_id' => $subject
            ]);

            $class->ps()->detach($psOld->id);
            $class->ps()->attach($ps->id);

            $prof = User::find($professor);

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste uredili nastavnika za ovaj predmet!'],
                'fields' => [
                    'professor' => $prof->name
                ]
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }
    }
    
    public function destroyPS($id, $psid) {
        $validator = Validator::make(['ps' => $psid], [
            'ps' => 'required|integer|exists:professor_subjects,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }

        try {
            $class = TheClass::find($id);

            $class->ps()->detach($psid);

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste uklonili ovaj predmet!'],
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
