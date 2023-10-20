<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // login
    public function indexLogin() {
        return view('authentications.login.index');
    }

    public function authenticate (Request $request) {
        $validatedData = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($validatedData)) {
            $request->session()->regenerate();
            if ($request->username == 'maintenance') {
                return redirect()->intended('/maintenance')->with('success', 'masuk');
            }
            if ($request->username == 'quality') {
                return redirect()->intended('/quality')->with('success', 'masuk');
            }
            if ($request->username == 'purchasing') {
                return redirect()->intended('/purchasing')->with('success', 'masuk');
            }
        }

        return redirect('/login')->with('fail', 'Username atau Password salah');

    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // register
    public function indexRegister() {
        return view('authentications.register.index');
    }

    public function store(Request $request) {
        $username = $request->username;
        $password = Hash::make($username);

        $result = User::where('username', $username)->count();

        if ($result > 0) {
            return redirect('/register')->with('fail', 'Username ini sudah ada!');
        }

        $user = new User();
        $user->username = $username;
        $user->password = $password;
        $user->save();

        return redirect('/register')->with('success', 'User baru berhasil ditambahkan!');
    }
}
