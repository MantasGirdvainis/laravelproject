<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Show register form
    public function register()
    {
        return view('users.register');
    }

    //Store a new user

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:3'],

        ]);

        // Hash password

        $formFields['password'] = bcrypt($formFields['password']);

        //Create user in database
        $user = User::create($formFields);

        //Login user after user was created
        auth()->login($user);

        return redirect('/')->with('message', 'User created and logged in!');
    }

    // logout method
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'you have been log out!');
    }

    // Show login view page
    public function login()
    {
        return view('users.login');
    }


    // Authenticate login
    public function authenticate(Request $request)
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($formFields)) {
            $request->session()->regenerate();
            return redirect('/')->with('message', 'You are now logged in!');
        }

        return back()->withErrors(['email' => 'Invalid Credentials'])->onlyInput('email');
    }
}
