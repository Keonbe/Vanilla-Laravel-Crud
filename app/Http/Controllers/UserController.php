<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function login(Request $request) {
        $incomingData = $request->validate([
            'loginname' => 'required',
            'loginpassword' => 'required',
        ]);

        if (auth()->attempt(['name' => $incomingData['loginname'], 'password' => $incomingData['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Logged in successfully!');
        }

        /*
        if (auth() -> attempt($incomingData)) {
            $request -> session() -> regenerate();
            return redirect('/') -> with('success', 'Logged in successfully!');
        }

        return back() -> withErrors(['email' => 'Invalid credentials provided']) -> onlyInput('email');
        */
    }

    public function logout(Request $request) {
        auth() -> logout();
        $request -> session() -> invalidate();
        $request -> session() -> regenerateToken();
        return redirect('/') -> with('success', 'Logged out successfully!');
    }

    public function register(Request $request) {
        // validate incoming data
        $incomingData = $request->validate([
            'name' => ['required', 'min:3', 'max:50', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|min:6|max:20',
        ]);
        // For unique: Rule::unique('users', 'email') & Rule::unique('users', 'name')

        // Hash the password and store the user
        $incomingData['password'] = bcrypt($incomingData['password']);
        $user = User::create($incomingData);
        auth() -> login($user);

        //return response()->json(['message' => 'User registered'], 201);
        return redirect('/') -> with('success', 'User registered successfully!');
    }
}
