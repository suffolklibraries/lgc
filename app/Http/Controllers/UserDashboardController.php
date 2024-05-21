<?php

namespace App\Http\Controllers;

use Statamic\View\View;
use Statamic\Facades\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UpdateUserDetailsRequest;
use App\Http\Requests\UpdateUserOrganisationRequest;

class UserDashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect(route('user.dashboard.my-details.index'));
    }

    public function details(): View
    {
        return (new View)
            ->layout('layout')
            ->template('users.dashboard.my-details')
            ->with([
                'title' => "Dashboard",
                'user' => Auth::user()
            ]);
    }

    public function updateDetails(UpdateUserDetailsRequest $request): RedirectResponse
    {

        $user = User::current();

        $user->data([
            'name' => $request->name,
        ]);

        $user->email($request->email);

        if($request->password && $request->new_password) {

            if(Hash::check($request->password, $user->password())) {
                $user->password(Hash::make($request->new_password));
            } else {
                return redirect()->back()->with([
                    'password_incorrect' => "Password Incorrect"
                ]);
            }
        }

        $user->save();

        return redirect()->back()->with([
            'success' => 'Details updated successfully'
        ]);
    }

    public function organisation(): View
    {
        return (new View)
            ->layout('layout')
            ->template('users.dashboard.my-organisation')
            ->with([
                'title' => "Dashboard",
                'user' => Auth::user()
            ]);
    }

    public function updateOrganisationDetails(UpdateUserOrganisationRequest $request): RedirectResponse
    {
        $user = User::current();

        $user->data([
            'organisation_name' => $request->organisation_name,
            'organisation_email' =>  $request->organisation_email,
            'organisation_tel' =>  $request->organisation_tel,
            'organisation_website' => $request->organisation_website,
        ]);

        $user->save();

        return redirect()->back()->with([
            'success' => 'Organisation details updated successfully'
        ]);
    }
}
