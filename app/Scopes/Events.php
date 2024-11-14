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

        // Virtual filtering
        $isVirtual = !empty($_GET['virtual']);
        if ($isVirtual) {
            $query->where('virtual', true);
        }

        // Location filtering
        $hasLat = !empty($values['latitude']);
        $hasLng = !empty($values['longitude']);

        if ($hasLat && $hasLng && !$isVirtual) {
            $lat = $hasLat ? $values['latitude'] : 52.058836;
            $lng = $hasLng ? $values['longitude'] : 1.156518;
            $radius = !empty($values['radius']) ? $values['radius'] : 99;

            // Convert radius from miles to kilometers
            $radiusKm = $radius * 1.60934;

            // Calculate min and max latitudes for the bounding box
            $minLat = $lat - ($radius / 69);
            $maxLat = $lat + ($radius / 69);

            // Calculate the latitude difference for the bounding box
            $deltaLon = asin(sin($radiusKm / 6371) / cos(deg2rad($lat)));

            // Calculate min and max longitudes for the bounding box
            $minLng = $lng - rad2deg($deltaLon);
            $maxLng = $lng + rad2deg($deltaLon);

            // Filter by latitude and longitude within the bounding box
            $query->where('latitude', '>=', $minLat)
                ->where('latitude', '<=', $maxLat)
                ->where('longitude', '>=', $minLng)
                ->where('longitude', '<=', $maxLng);
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
    }
}
