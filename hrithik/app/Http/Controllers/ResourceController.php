<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Category;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    // ══════════════════════════════
    // MODULE 1 — View Resource Profile
    // ══════════════════════════════
    public function show(Resource $resource)
    {
        if (!$resource->is_approved && auth()->id() !== $resource->user_id) {
            abort(404);
        }

        $resource->increment('view_count');
        $resource->load(['owner', 'category', 'reviews.reviewer']);

        return view('resources.show', compact('resource'));
    }

    // MODULE 1 — Edit Resource (GET)
    public function edit(Resource $resource)
    {
        $this->authorize('update', $resource);
        $categories = Category::all();
        return view('resources.edit', compact('resource', 'categories'));
    }

    // MODULE 1 — Save Edit (PUT)
    public function update(Request $request, Resource $resource)
    {
        $this->authorize('update', $resource);

        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'required|string|min:10',
            'category_id'         => 'required|exists:categories,id',
            'condition'           => 'required|in:new,good,fair,poor',
            'availability_status' => 'required|in:available,borrowed,unavailable',
            'availability_until'  => 'nullable|date',
            'pickup_address'      => 'nullable|string|max:255',
            'pickup_lat'          => 'nullable|numeric',
            'pickup_lng'          => 'nullable|numeric',
        ]);

        $resource->update($validated);

        return redirect()->route('resources.show', $resource)
                         ->with('success', '✅ Resource updated successfully!');
    }
}
