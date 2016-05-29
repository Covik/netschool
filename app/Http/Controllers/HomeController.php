<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Mockery\CountValidator\Exception;
use Validator;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class HomeController extends Controller {
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    public function __construct() {
        $this->middleware('auth', ['only' => 'logout']);
    }

    public function index() {
        if(Auth::check()) {
            $user = Auth::user();

            if($user->isAdmin()) return redirect('/files');

            \Menu::get('navigation')->pocetna->active();
            $roles = array_flip(config('roles'));

            $role = $roles[$user->role];

            return view('home.'.$role);
        }
        else return view('home.guests');
    }

    public function signin(Request $request) {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()) return response()->json([
            'success' => false,
            'output' => $validator->errors()->all()
        ]);

        if(!empty($student = User::where('email', '=', $request->input('email'))->where('role', '=', config('roles.student'))->first()) && $student->class_id === null) return response()->json([
            'success' => false,
            'output' => ['Vaš račun nije potvrđen!']
        ]);

        if(Auth::attempt($credentials, $request->has('remember'))) {
            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste prijavljeni. Preusmjeravanje...']
            ]);
        }
        else return response()->json([
            'success' => false,
            'output' => ['E-mail i/ili lozinka nisu točni!']
        ]);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()) return response()->json([
            'success' => false,
            'output' => $validator->errors()->all()
        ]);

        try {
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password'))
            ]);

            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste se registrirali.', 'Administrator treba potvrditi vašu registraciju!']
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'output' => ['Dogodila se neočekivana greška!']
            ]);
        }
    }

    public function logout() {
        Auth::logout();
        return redirect('/');
    }
}
