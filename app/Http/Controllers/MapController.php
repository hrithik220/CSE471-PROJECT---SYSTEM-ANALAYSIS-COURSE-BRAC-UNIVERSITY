<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class MapController extends Controller
{
    // Feature 4: Map view
    public function index()
    {
        return view('map.index');
    }

    // JSON endpoint for Leaflet markers
    public function getResourceLocations()
    {
        $resources = Resource::with('owner')
            ->available()
            ->whereNotNull('location_lat')
            ->whereNotNull('location_lng')
            ->get()
            ->map(function ($r) {
                return [
                    'id'          => $r->id,
                    'title'       => $r->title,
                    'type'        => $r->type,
                    'category'    => $r->category,
                    'condition'   => $r->condition,
                    'owner'       => $r->owner->name,
                    'credibility' => $r->owner->credibility_score,
                    'lat'         => $r->location_lat,
                    'lng'         => $r->location_lng,
                    'location'    => $r->location_name,
                    'photo_url'   => $r->photo_url,
                    'url'         => route('resources.show', $r->id),
                ];
            });

        return response()->json($resources);
    }
}
