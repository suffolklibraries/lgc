<?php

namespace App\Tags;

use Statamic\Tags\Tags;
use Illuminate\Support\Facades\Route;

class CurrentRouteIs extends Tags
{
    /**
     * The {{ current_route_is }} tag.
     *
     * @return string|array
     */
    public function index()
    {
        $route = Route::currentRouteName();

        return $this->params->get('route') === $route;
    }

}
