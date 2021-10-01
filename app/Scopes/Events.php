<?php

namespace App\Scopes;

use Statamic\Query\Scopes\Scope;

class Events extends Scope
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
        // Only show future events
        $thisMorning = date('Y-m-d H:i:s', strtotime('today'));
        $query->where('end_date', '>=', $thisMorning);

        // Location filtering
        $hasLat = !empty($values['latitude']);
        $hasLng = !empty($values['longitude']);
        
        if ($hasLat && $hasLng) {
            $lat = $hasLat ? $values['latitude'] : 52.058836;
            $lng = $hasLng ? $values['longitude'] : 1.156518;
            $radius = !empty($values['radius']) ? $values['radius'] : 99;

            $mileInLat = 0.0291519;
            $mileInLng = 0.0471264;

            $query->where('latitude', '>=', $lat - ($mileInLat * $radius));
            $query->where('latitude', '<=', $lat + ($mileInLat * $radius));
            $query->where('longitude', '>=', $lng - ($mileInLng * $radius));
            $query->where('longitude', '<=', $lng + ($mileInLng * $radius));
        }

        // Category filtering
        $hasCat = !empty($_GET['category']);
        if ($hasCat) {
            $categories = $_GET['category'];
            if (is_array($categories) && count($categories)) {
                $namedCategories = [];
                foreach ($categories as $category) {
                    $namedCategories[] = 'event_categories::' . $category;
                }
                $query->whereTaxonomyIn($namedCategories);
            }
        }

        // Free filtering
        if (!empty($_GET['free'])) {
            $query->where('free', true);
        }

        // Virtual filtering
        if (!empty($_GET['virtual'])) {
            $query->where('virtual', true);
        }
    }
}
