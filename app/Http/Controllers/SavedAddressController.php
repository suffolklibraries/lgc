<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavedAddress\DeleteRequest;
use Statamic\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Statamic\Taxonomies\LocalizedTerm;
use App\Http\Requests\SavedAddress\StoreRequest;
use App\Http\Requests\SavedAddress\UpdateRequest;

class SavedAddressController extends Controller
{
    public function index(): View
    {
        if(Auth::user()->linked_organisations) {
            $org = Auth::user()->linked_organisations;
        } else {
            $org = null;
        }

        return (new View)
            ->layout('layout')
            ->template('users.dashboard.saved-addresses.index')
            ->with([
                'title' => "Dashboard",
                'org' => $org
            ]);
    }

    public function create(): View
    {
        if(Auth::user()->linked_organisations) {
            $org = [Auth::user()->linked_organisations?->slug];
        } else {
            $org = null;
        }

        return (new View)
            ->layout('layout')
            ->template('users.dashboard.saved-addresses.create')
            ->with([
                'title' => "Dashboard",
                'org' => $org
            ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {

        $org = Auth::user()->linked_organisations;

        if(!$org) {
            abort(404);
        }

        $newAddress = [
            'id' => (string) Str::uuid(),
            'name' => $request->name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'town' => $request->town,
            'postcode' => $request->postcode,
            'lat' => $request->lat,
            'lng' => $request->lng,
        ];

        $addresses = $org->get('addresses');

        $addresses[] = $newAddress;

        $org->set('addresses', $addresses);

        $org->save();

        return redirect(route('user.dashboard.saved-addresses.index'))->with(
            'success',
            __("Address ':name' created successfully.", [
                'name' => $request->name
            ])
        );
    }

    public function edit(string $id): View
    {

        $org = $this->getOrganisation();
        $address = $this->getAddress($org, $id);

        return (new View)
            ->layout('layout')
            ->template('users.dashboard.saved-addresses.edit')
            ->with([
                'title' => "Dashboard",
                'address' => $address
            ]);
    }

    public function update(UpdateRequest $request, string $id): RedirectResponse
    {
        $org = $this->getOrganisation();
        $addressIndex = $this->getAddressIndex($org, $id);
        $address = $this->getAddress($org, $id);
        $addresses = $org->get('addresses');

        $addresses[$addressIndex] = array_merge($addresses[$addressIndex], [
            'name' => $request->name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'town' => $request->town,
            'postcode' => $request->postcode,
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        $org->set('addresses', $addresses);
        $org->save();

        return redirect(route('user.dashboard.saved-addresses.index'))->with(
            'success',
            __("Address ':name' updated successfully.", [
                'name' => $address['name'] === $request->name ? $address['name'] : $request->name
            ])
        );
    }

    public function delete(DeleteRequest $request, string $id): RedirectResponse
    {
        $org = $this->getOrganisation();
        $addressIndex = $this->getAddressIndex($org, $id);
        $address = $this->getAddress($org, $id);
        $addresses = $org->get('addresses');

        unset($addresses[$addressIndex]);
        $addresses = array_values($addresses);

        $org->set('addresses', $addresses);
        $org->save();

        return redirect(route('user.dashboard.saved-addresses.index'))->with(
            'success',
            __("Address ':name' deleted successfully.", [
                'name' => $address['name']
            ])
        );
    }

    private function getOrganisation()
    {
        $org = Auth::user()->linked_organisations;

        if(!$org) {
            abort(404);
        }

        return $org;
    }

    private function getAddress(LocalizedTerm $org, string $id): array|false
    {
        $addresses = $org->get('addresses');

        $address = collect($addresses)->filter(function($address) use ($id) {
            return $address['id'] === $id;
        })->first();

        if(!$address) {
            return false;
        }

        return $address;
    }

    private function getAddressIndex(LocalizedTerm $org, string $id): int|false
    {
        $addresses = $org->get('addresses');

        $index = collect($addresses)->search(function($address) use ($id) {
            return $address['id'] === $id;
        });

        if(!$index) {
            return false;
        }

        return $index;
    }
}
