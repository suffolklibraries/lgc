<?php

namespace App\Scopes;

use Statamic\Query\Scopes\Scope;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

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


        // Location filtering
        $hasLat = !empty($values['latitude']);
        $hasLng = !empty($values['longitude']);

        if ($hasLat && $hasLng && !$isVirtual) {
            $this->applyLocationFiltering($query, $values, $hasLat, $hasLng);
        }

    }

    private function applyLocationFiltering($query, $values, $hasLat, $hasLng)
    {
        $entries = $query->get()
            ->filter(function($item) {
                return $item->latitude && $item->longitude;
            })
            ->map(function($entry) {
                return [
                    'id' => $entry->id,
                    'lat' => $entry->latitude,
                    'lng' => $entry->longitude
                ];
            })
            ->values();

        $lat = $hasLat ? $values['latitude'] : 52.058836;
        $lng = $hasLng ? $values['longitude'] : 1.156518;


        $radius = !empty($values['radius']) ? $values['radius'] : false;

        if($radius) {
            // Convert radius from miles to meters
            $radiusInMetres = $radius * 1609.344;

            $cacheKey = "distance_matrix_{$lat}_{$lng}_{$radiusInMetres}_". md5(json_encode($entries));

            $filteredIds =  Cache::remember($cacheKey, now()->addHours(24), function () use ($entries, $lat, $lng, $radiusInMetres) {
                $destinationChunks = $entries
                    ->map(function($item) {
                        return "{$item['lat']},{$item['lng']}";
                    })
                    ->chunk(20);

                $results = [];

                $destinationChunks->each(function($chunk, $chunkIndex) use ($lat, $lng, &$results, &$queries) {

                    $destinations = $chunk->implode("|");

                    $response = Http::get(
                        "https://maps.googleapis.com/maps/api/distancematrix/json",
                        [
                            "origins" => "{$lat},{$lng}",
                            "destinations" => $destinations,
                            "key" => config("app.google_maps"),
                            'units' => 'imperial'
                        ]
                    );

                    $results = array_merge($results, $response->json()['rows'][0]['elements']);
                });

                $filteredIds = [];

                foreach($results as $index => $result) {
                    if($result['distance']['value'] <= round($radiusInMetres)) {
                        $filteredIds[] = $entries[$index]['id'];
                    }
                }

                return $filteredIds;
            });


            $query->whereIn('id', $filteredIds);
        }
    }
}
