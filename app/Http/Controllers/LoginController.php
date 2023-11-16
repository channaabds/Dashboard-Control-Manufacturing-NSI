<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            $departement = User::where('username', $request->username)->first()->get('departement');
            if ($departement == 'it') {
                return redirect()->intended('/menu')->with('success', 'Login berhasil');
            }
            if ($departement == 'maintenance') {
                return redirect()->intended('/maintenance')->with('success', 'Login berhasil');
            }
            if ($departement == 'qc' || $departement == 'qa') {
                return redirect()->intended('/quality')->with('success', 'Login berhasil');
            }
            if ($departement == 'purchasing') {
                return redirect()->intended('/purchasing')->with('success', 'Login berhasil');
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
        $password = Hash::make($request->password);

        $result = User::where('username', $username)->count();

        if ($result > 0) {
            return redirect('/menu/register')->with('fail', 'Username ini sudah ada!');
        }

        $user = new User();
        $user->username = $username;
        $user->departement = $request->departement;
        $user->role = $request->role;
        $user->password = $password;
        $user->save();

        return redirect('/menu/register')->with('success', 'User baru berhasil ditambahkan!');
    }
}
