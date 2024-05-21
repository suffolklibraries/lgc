<?php

namespace App\Http\Controllers;

use Statamic\Facades\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    public function register()
    {
        return (new \Statamic\View\View)
            ->layout('layout')
            ->template('users.register')
            ->with([
                'title' => "Register"
            ]);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::make()
            ->email($request->email)
            ->password($request->password)
            ->data([
                'name' => $request->name
            ])
            ->roles(['user'])
            ->save();

        Auth::login($user);

        return redirect(route('user.dashboard'));
    }
}
