<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Validator;

class ProfessorsController extends Controller
{
    private $rules = [
        'name' => 'required|string|max:50',
        'email' => 'required|email|max:255'
    ];

    public function index() {
        $professors = User::professors()->get();

        return view('admin.professors', compact('professors'));
    }

    public function store(Request $request) {
        $rules = $this->rules;
        $rules['password'] = 'required|string|max:65|min:6|confirmed';

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'output' => $validator->errors()->all()
            ]);
        }

        try {
            $professor = new User();

            $professor->name = $request->input('name');
            $professor->email = $request->input('email');
            $professor->role = config('roles.professor');

            $professor->save();

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste dodali novog nastavnika!'],
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
            $professor = User::find($id);

            $professor->name = $request->input('name');
            $professor->email = $request->input('email');

            $professor->save();

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste uredili ovog nastavnika!'],
                'fields' => [
                    'name' => $professor->name,
                    'email' => $professor->email
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
            $professor = User::find($id);

            $professor->delete();

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
}
