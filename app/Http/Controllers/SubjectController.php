<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use App\Subject;

class SubjectController extends Controller
{
    private $rules = [
        'name' => 'required|string|max:60'
    ];

    public function index() {
        $subjects = Subject::with('courses')->get();
        
        return view('admin.subjects.index', compact('subjects'));
    }

    public function single($slug) {
        $subject = Subject::where('slug', '=', $slug)->first();

        if($subject === null) abort(404);

        $courses = Course::all();

        return view('admin.subjects.single', compact('subject', 'courses'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), $this->rules);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'output' => $validator->errors()->all()
            ]);
        }

        try {
            $subject = new Subject();

            $subject->name = $request->input('name');
            $subject->slug = str_slug($subject->name, '-');

            $subject->save();

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste dodali novi predmet!'],
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }
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
            $subject = Subject::find($id);

            $subject->name = $request->input('name');

            $subject->save();

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste uredili ovaj predmet!'],
                'fields' => [
                    'name' => $subject->name
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
            $subject = Subject::find($id);

            $subject->delete();

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste uklonili ovog nastavnika!'],
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }
    }

    public function saveCourses(Request $request, $slug) {
        try {
            $subject = Subject::where('slug', '=', $slug)->first();

            $response = (object) [
                'added' => 0,
                'removed' => 0
            ];

            foreach ($request->input('courses') as $course => $years) {
                foreach ($years as $year => $value) {
                    $year = $year + 1;
                    $exists = count($subject->courses()->where('course_id', '=', $course)->where('course_year', '=', $year)->get());

                    if ($value === 'true' && $exists == 0) {
                        $subject->courses()->attach($course, ['course_year' => $year]);
                        $response->added ++;
                    } else if ($value === 'false' && $exists == 1) {
                        \DB::table('courses_subjects')->where('course_id', '=', $course)->where('course_year', $year)->where('subject_id', '=', $subject->id)->delete();
                        $response->removed ++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'output' => [
                    'Dodano: '.$response->added,
                    'Uklonjeno: '.$response->removed
                ]
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }
    }
}
