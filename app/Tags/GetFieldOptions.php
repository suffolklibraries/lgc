<?php

namespace App\Tags;

use Statamic\Tags\Tags;
use Statamic\Facades\Collection;

class GetFieldOptions extends Tags
{
    /**
     * The {{ get_field_options }} tag.
     *
     * @return string|array
     */
    public function index()
    {
        return Collection::find($this->params->get('collection'))
            ->entryBlueprint()
            ->field($this->params->get('field'))
            ->get("options");
    }
}
