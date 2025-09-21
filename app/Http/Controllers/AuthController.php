<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
	public function registerPage() {
    return view('pages.register');
  }

  public function registerProcess(Request $request) {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255|unique:users',
      'password' => 'required|string|min:8|confirmed',
    ], [
      'password.confirmed' => 'Confirm password does not match',
    ]);

    User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role' => 'user',
    ]);

    $login = [
      'name' => $request->name,
      'password' => $request->password,
    ];

    if (Auth::attempt($login)) {
      return redirect()->route('formUpdateDataUser')->with('success', 'Acount Berhasil Dibuat Lengkapi Data Anda.');
    } else {
      return redirect()->route('registerPage')->with('failed', 'Incorrect Username, Email or Password');
    }
  }

  // login 
  public function loginPage() {
    return view('pages.login');
  }

  public function loginProcess(Request $request) {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');
    $remember = $request->has('remember');

    if (Auth::attempt($credentials, $remember)) {
      $user = Auth::user();
      if ($user->role !== 'user') {
        Auth::logout();
        return redirect()->route('loginPage')->withErrors(['email' => 'Unauthorized access.']);
      }
      return redirect()->route('home');
    }
    return back()->withErrors(['email' => 'Invalid credentials.']);
  }

  // logout 
  public function logout() {
    Auth::logout();
    return redirect()->route('loginPage')->with('success', 'You have Successfully Logout');
  }
}
