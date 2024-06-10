<?php

namespace App\Scopes;

use Statamic\Facades\User;
use Statamic\Query\Scopes\Scope;

class UserEvents extends Scope
{
    /**
     * Apply the scope.
     *
     * @param \Statamic\Query\Builder $query
     * @param array $values
     * @return void
     */
    public function apply($query, $values)
    {
        $user = User::current();
        $query->where('created_by', $user->id);
    }
}
