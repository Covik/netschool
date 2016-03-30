<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Validator;
use Auth;
use Hash;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class HomeController extends Controller {
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    public function index() {
        if(Auth::check()) return view('home.logged', ['user' => Auth::user()]);
        else return view('home.guests');
    }

    public function signin(Request $request) {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()) return response()->json([
            'success' => false,
            'output' => $validator->errors()->all()
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

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password'))
        ]);

        if($user) {
            Auth::login($user);
            return response()->json([
                'success' => true,
                'output' => ['Uspješno ste se registrirali. Preusmjeravanje...']
            ]);
        }
    }
}
