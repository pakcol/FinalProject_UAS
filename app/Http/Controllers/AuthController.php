<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kingdom;
use App\Models\Tribe;
use App\Models\Troop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showRegister()
    {
        $tribes = Tribe::all();
        return view('auth.register', compact('tribes'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'kingdom_name' => 'required|string|max:255|unique:kingdoms,name',
            'tribe_id' => 'required|exists:tribes,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $kingdom = Kingdom::create([
            'user_id' => $user->id,
            'tribe_id' => $request->tribe_id,
            'name' => $request->kingdom_name,
            'gold' => 100,
            'main_building_level' => 1,
            'last_resource_update' => now(),
        ]);

        Troop::create([
            'kingdom_id' => $kingdom->id,
            'quantity' => 10,
            'last_production_update' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('game.dashboard');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('game.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}