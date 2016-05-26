<?php

namespace App\Http\Controllers;

use App\TheClass;
use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;
use App\User;

class StudentController extends Controller
{
    public function index() {
        $students = User::students()->orderBy('class_id', 'ASC')->get();

        $classes = TheClass::all();

        $allClasses = '{0: \'Odaberi razred\',';

        foreach ($classes as $class) {
            $allClasses .= $class->id.': \''.$class->name.'\',';
        }

        $allClasses .= '}';
        
        return view('admin.students', compact('students', 'allClasses'));
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'class' => 'required|integer|exists:classes,id'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'output' => $validator->errors()->all()
            ]);
        }

        try {
            $student = User::find($id);

            $student->name = $request->input('name');
            $student->email = $request->input('email');
            $student->class_id = $request->input('class');

            $student->save();

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste uredili ovog učenika!'],
                'fields' => [
                    'name' => $student->name,
                    'email' => $student->email,
                    'class' => $student->theclass->name
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
            $student = User::find($id);

            $student->delete();

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste uklonili ovog učenika!'],
            ]);
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }
    }
}
