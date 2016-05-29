<?php

namespace App\Http\Controllers;

use App\TheClass;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\File;
use Mockery\CountValidator\Exception;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File as F;

class FileController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except' => 'get']);
    }

    public function index() {
        $user = Auth::user();

        if($user->isAdmin()) $files = File::all();
        else $files = File::where('user_id', '=', $user->id)->get();

        return view('home.files.index', compact('files'));
    }

    public function get($id, $name) {
        $path = 'files/'.$id.'/'.$name;

        if(!Storage::exists($path)) abort(404);

        $file = Storage::get($path);

        return response($file)->header('Content-Type', Storage::mimeType($path));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
            'class' => 'required|exists:classes,id',
            'subject' => 'required|exists:professor_subjects,id'
        ]);

        if($validator->fails()) return response()->json([
            'success' => false,
            'output' => $validator->errors()->all()
        ]);

        $file = $request->file('file');
        $class = $request->input('class');
        $subject = $request->input('subject');

        $user = Auth::user();

        try {
            if($file->isValid()) {
                $cls = TheClass::findOrFail($class);

                $f = new File();
                $f->filename = $file->getClientOriginalName();
                $f->description = '';
                $f->user_id = $user->id;
                $f->subject_id = $subject;
                $f->class_id = $class;
                $f->class_year = $cls->getClassNumber();

                $f->save();

                $path = 'files/'.$f->id;
                Storage::makeDirectory($path);
                $path .= '/'.$f->filename;
                Storage::put($path, F::get($file));

                return response()->json([
                    'success' => true,
                    'html' => view('home.files.single', ['file' => $f])->render(),
                ]);
            }
            else return response()->json([
                'success' => true,
                'output' => ['Prijenos ne uspješan!']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => true,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->only(['description']), [
            'description' => 'string'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'output' => $validator->errors()->all()
            ]);
        }

        try {
            $file = File::findOrFail($id);

            $file->description = $request->input('description');

            $file->save();

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste uredili ovu datoteku!'],
                'fields' => [
                    'description' => $file->description
                ]
            ]);
        } catch(Exception $e) {
            return response()->json([
                'success' => false,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }
    }

    public function destroy($id) {
        try {
            $file = File::findOrFail($id);

            Storage::deleteDirectory('files/'.$id);

            $file->delete();

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste uklonili ovu datoteku!'],
            ]);
        } catch(Exception $e) {
            return response()->json([
                'success' => false,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }
    }
}
