<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
  // update data user sebelum ke homepage
  public function formUpdateDataUser() {
    $user = Auth::user()->id;
    if ($user) {
      return view('pages.formInputDataUser');
    }
    return redirect()->route('loginPage');
  }

	public function updateDataUserProcess(Request $request) {
    $request->validate([
      'no_hp' => 'required|string|max:15',
      'addres' => 'required|string',
    ]);

    $user = Auth::user();
    $user->update([
      'no_hp' => $request->no_hp,
      'addres' => $request->addres,
    ]);

    return redirect()->route('home')->with('success', 'Data Berhasil Disimpan!');
  }

	// profile 
  public function profilePage() {
    $userDetail = Auth::user();
    if ($userDetail) {
      return view('pages.profile', compact('userDetail'));
    }
  }

	public function updateProfilePage() {
    $user = Auth::user();
    if ($user) {
      return view('pages.updateProfile', compact('user'));
    }
    return redirect()->route('loginPage');
  }

	public function updateProfile(Request $request) {
    $user = Auth::user();

    $request->validate([
      'name' => 'required|string|max:255',
      'no_hp' => 'required|numeric',
      'addres' => 'required|string',
      'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
      'user_img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ], [
      'no_hp.numeric' => 'No Telp harus berupa angka',
    ]);

    $userData = [
      'name' => $request->name,
      'no_hp' => $request->no_hp,
      'addres' => $request->addres,
      'email' => $request->email,
    ];

    // delete foto lama 
    if ($request->hasFile('user_img')) {
      if ($user->user_img) {
        Storage::disk('public')->delete('profile/' . $user->user_img);
      }

      // hash & Upload foto baru
      $fileName = time() . '.' . $request->file('user_img')->extension();
      $request->file('user_img')->storeAs('profile', $fileName, 'public');

      $userData['user_img'] = $fileName;
    }

    $user->update($userData);

    return redirect()->route('profilePage')->with('success', 'Profile berhasil diupdate.');
  }
}
