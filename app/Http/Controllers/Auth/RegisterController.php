<?php

namespace App\Http\Controllers\Auth;

use Statamic\View\View;
use Statamic\Facades\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreUserRequest;

class RegisterController extends Controller
{
    public function index(): View
    {
        return (new View)
            ->layout('layout')
            ->template('users.register')
            ->with([
                'title' => "Register"
            ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::make()
        ->email($request->email)
        ->password($request->password)
        ->data([
            'name' => $request->name,
            'organisation_name' => $request->organisation_name,
            'organisation_email' => $request->organisation_email,
            'organisation_tel' => $request->organisation_tel,
            'organisation_website' => $request->organisation_website,
        ])
        ->roles(['user'])
        ->save();

        Auth::login($user);

        return redirect(route('user.dashboard.index'));
    }
}
