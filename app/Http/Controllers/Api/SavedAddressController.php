<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SavedAddressController extends Controller
{
    public function index()
    {
        $org = $this->getOrganisation();
        return $org->addresses;
    }

    private function getOrganisation()
    {
        $org = Auth::user()->linked_organisations;

        if(!$org) {
            abort(404);
        }

        return $org;
    }
}
