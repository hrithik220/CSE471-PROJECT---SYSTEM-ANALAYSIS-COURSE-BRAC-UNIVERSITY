<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Category;
use Illuminate\Http\Request;

class MapController extends Controller
{
    // ══════════════════════════════
    // MODULE 2 — Pickup Locator Map Page
    // ══════════════════════════════
    public function index(Request $request)
    {
        $query = Resource::approved()
            ->with(['owner', 'category'])
            ->whereNotNull('pickup_lat')
            ->whereNotNull('pickup_lng')
            ->where('availability_status', 'available');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $resources   = $query->get();
        $categories  = Category::all();
        // BUG FIX: env() should never be called directly in controllers — it returns
        // null when config is cached (php artisan config:cache). Use config() instead.
        // Also added the key to config/services.php (see that file).
        $apiKey      = config('services.google_maps.key', '');

        return view('map.index', compact('resources', 'categories', 'apiKey'));
    }

    // ══════════════════════════════
    // MODULE 2 — Get resources as JSON (for map pins)
    // ══════════════════════════════
    public function getMapData(Request $request)
    {
        $resources = Resource::approved()
            ->with(['owner', 'category'])
            ->whereNotNull('pickup_lat')
            ->whereNotNull('pickup_lng')
            ->where('availability_status', 'available')
            ->get()
            ->map(function ($r) {
                return [
                    'id'           => $r->id,
                    'title'        => $r->title,
                    'category'     => $r->category->name,
                    'icon'         => $r->category->icon,
                    'condition'    => $r->condition,
                    'sharing_type' => $r->sharing_type,
                    'owner'        => $r->owner->name,
                    'credibility'  => $r->owner->credibility_score,
                    'pickup_lat'   => $r->pickup_lat,
                    'pickup_lng'   => $r->pickup_lng,
                    'pickup_address' => $r->pickup_address,
                    'url'          => route('resources.show', $r->id),
                ];
            });

        return response()->json($resources);
    }

    // ══════════════════════════════
    // MODULE 2 — Update pickup location
    // ══════════════════════════════
    public function updateLocation(Request $request, Resource $resource)
    {
        $this->authorize('update', $resource);

        $validated = $request->validate([
            'pickup_lat'     => 'required|numeric',
            'pickup_lng'     => 'required|numeric',
            'pickup_address' => 'required|string|max:255',
        ]);

        $resource->update($validated);

        return response()->json([
            'message' => 'Pickup location updated successfully!',
            'resource' => $resource,
        ]);
    }
}
