<?php

namespace App\Http\Controllers\Auth;

use Statamic\View\View;
use Statamic\Facades\Term;
use Statamic\Facades\User;
use Illuminate\Support\Str;
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
        $orgSlug = Str::slug($request->organisation_name);

        // Search for organisation first
        $organisation = Term::query()
            ->where('taxonomy', 'organisers')
            ->where('slug',  $orgSlug)
            ->first();

        if(!$organisation) {
            $organisation = Term::make()
                ->taxonomy('organisers')
                ->slug($orgSlug)
                ->data([
                    'title' => $request->organisation_name,
                    'email_address' => $request->organisation_email,
                    'telephone_number' => $request->organisation_tel,
                    'website' => $request->organisation_website
                ]);

            $organisation->save();
        }


        $user = User::make()
            ->email($request->email)
            ->password($request->password)
            ->data([
                'name' => $request->name,
                'linked_organisations' => $organisation->slug()
            ])
            ->roles(['user'])
            ->save();

        Auth::login($user);

        return redirect(route('user.dashboard.index'));
    }
}
