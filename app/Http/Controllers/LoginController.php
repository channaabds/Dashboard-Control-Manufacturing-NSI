<?php

namespace App\Http\Controllers;

use App\Models\UserTest;
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

    // public function authenticate (Request $request) {
    //     $validatedData = $request->validate([
    //         'username' => 'required',
    //         'password' => 'required',
    //     ]);

    //     if (Auth::attempt($validatedData)) {
    //         $request->session()->regenerate();
    //         if ($request->username == 'manager') {
    //             return redirect()->intended('/menu')->with('success', 'Login berhasil');
    //         }
    //         if ($request->username == 'maintenance') {
    //             return redirect()->intended('/maintenance')->with('success', 'Login berhasil');
    //         }
    //         if ($request->username == 'qc' || $request->username == 'qa') {
    //             return redirect()->intended('/quality')->with('success', 'Login berhasil');
    //         }
    //         if ($request->username == 'purchasing') {
    //             return redirect()->intended('/purchasing')->with('success', 'Login berhasil');
    //         }
    //     }

    //     return redirect('/login')->with('fail', 'Username atau Password salah');

    // }
    public function authenticate (Request $request) {
        $validatedData = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($validatedData)) {
            $request->session()->regenerate();
            if ($request->username == 'manager') {
                return redirect()->intended('/menu')->with('success', 'Login berhasil');
            }
            if ($request->username == 'maintenance') {
                return redirect()->intended('/maintenance')->with('success', 'Login berhasil');
            }
            if ($request->username == 'qc' || $request->username == 'qa') {
                return redirect()->intended('/quality')->with('success', 'Login berhasil');
            }
            if ($request->username == 'purchasing') {
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

        $result = UserTest::where('username', $username)->count();

        if ($result > 0) {
            return redirect('/register')->with('fail', 'Username ini sudah ada!');
        }

        $user = new UserTest();
        $user->username = $username;
        $user->departement = $request->departement;
        $user->role = $request->role;
        $user->password = $password;
        $user->save();

        return redirect('/register')->with('success', 'User baru berhasil ditambahkan!');
    }
    // public function store(Request $request) {
    //     $username = Str::lower($request->username);
    //     $password = Hash::make($username);

    //     $result = User::where('username', $username)->count();

    //     if ($result > 0) {
    //         return redirect('/register')->with('fail', 'Username ini sudah ada!');
    //     }

    //     $user = new User();
    //     $user->username = $username;
    //     $user->password = $password;
    //     $user->save();

    //     return redirect('/register')->with('success', 'User baru berhasil ditambahkan!');
    // }
}
