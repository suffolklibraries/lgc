<?php

namespace App\Http\Controllers\Auth;

use Statamic\View\View;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function index(): View
    {
        return (new View)
            ->layout('layout')
            ->template('users.login')
            ->with([
                'title' => "Login"
            ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        $remember = $request->has('remember');

        if(! Auth::attempt($credentials, $remember)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        return redirect(route('user.dashboard.index'));
    }
}
