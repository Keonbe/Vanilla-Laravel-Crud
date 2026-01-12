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
        ]); // validate incoming data

        // attempt to log the user in
        if (auth()->attempt(['name' => $incomingData['loginname'], 'password' => $incomingData['loginpassword']])) {
            $request->session()->regenerate(); // prevent session fixation
            return redirect('/')->with('success', 'Logged in successfully!'); // redirect to homepage with success message
        }

        return back()->withErrors(['loginname' => 'Invalid username or password'])->onlyInput('loginname'); // return back with error message

    }

    // Logout user
    public function logout(Request $request) {
        auth() -> logout(); // log the user out
        $request -> session() -> invalidate(); // invalidate the session
        $request -> session() -> regenerateToken(); // regenerate CSRF token
        return redirect('/') -> with('success', 'Logged out successfully!'); // redirect to homepage with success message
    }

    // Register new user
    public function register(Request $request) {
        // validate incoming data; must follow rules in between braces for each field
        $incomingData = $request->validate([
            'name' => ['required', 'min:3', 'max:50', Rule::unique('users', 'name')],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|min:6|max:20',
        ]);
        // For unique: Rule::unique('users', 'email') & Rule::unique('users', 'name')

        // Hash the password and store the user
        $incomingData['password'] = bcrypt($incomingData['password']); // hash the password before storing
        $user = User::create($incomingData); // create new user in the database
        auth() -> login($user); // log the user in after registration

        //return response()->json(['message' => 'User registered'], 201);
        return redirect('/') -> with('success', 'User registered successfully!'); // redirect to homepage with success message
    }
}
