<?php

namespace App\Http\Controllers\Auth;

use Statamic\View\View;
use Statamic\Facades\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\ForgotPasswordRequest;

class ForgotPasswordController extends Controller
{
    public function index(): View
    {
        return (new View)
            ->layout('layout')
            ->template('users.forgot-password')
            ->with([
                'title' => "Forgotten Password"
            ]);
    }

    public function sendResetLink(ForgotPasswordRequest $request): RedirectResponse
    {
        // $user = User::findByEmail($request->email);

        // if(!$user) {
        //     return redirect()->back()->withErrors([
        //         'email' => __('passwords.user')
        //     ]);
        // }

        // Send email
        $status = Password::sendResetLink([
            'email' => $request->email
        ]);

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['message' => __('passwords.sent')])
            : back()->withErrors(['email' => __($status)]);
    }
}
