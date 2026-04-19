<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    // Feature 1: Browse all available resources
    public function index(Request $request)
    {
        $query = Resource::with('owner')->available();

        if ($request->filled('type')) {
            $query->byType($request->type);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $resources = $query->latest()->paginate(12);
        return view('resources.index', compact('resources'));
    }

    // Feature 1: Show create form
    public function create()
    {
        return view('resources.create');
    }

    // Feature 1: Store new resource
    public function store(Request $request)
    {
        $request->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'required|string',
            'category'           => 'required|string|max:100',
            'type'               => 'required|in:free_lending,exchange',
            'availability_start' => 'required|date',
            'availability_end'   => 'required|date|after:availability_start',
            'condition'          => 'required|in:excellent,good,fair,poor',
            'location_name'      => 'nullable|string|max:255',
            'location_lat'       => 'nullable|numeric',
            'location_lng'       => 'nullable|numeric',
            'photo'              => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('resources', 'public');
        }

        Resource::create([
            'owner_id'           => Auth::id(),
            'title'              => $request->title,
            'description'        => $request->description,
            'category'           => $request->category,
            'type'               => $request->type,
            'availability_start' => $request->availability_start,
            'availability_end'   => $request->availability_end,
            'condition'          => $request->condition,
            'location_name'      => $request->location_name,
            'location_lat'       => $request->location_lat,
            'location_lng'       => $request->location_lng,
            'photo'              => $photoPath,
            'status'             => 'available',
        ]);

        return redirect()->route('resources.index')->with('success', 'Resource posted successfully!');
    }

    // Feature 2: Show resource details with owner credibility, history, reviews
    public function show(Resource $resource)
    {
        $resource->load(['owner.reviewsReceived.reviewer', 'reviews.reviewer', 'transactions' => function ($q) {
            $q->where('status', 'returned')->latest()->take(5);
        }]);

        $alreadyReviewed = Review::where('resource_id', $resource->id)
            ->where('reviewer_id', Auth::id())
            ->exists();

        return view('resources.show', compact('resource', 'alreadyReviewed'));
    }

    public function edit(Resource $resource)
    {
        $this->authorize('update', $resource);
        return view('resources.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $this->authorize('update', $resource);

        $request->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'required|string',
            'category'           => 'required|string|max:100',
            'type'               => 'required|in:free_lending,exchange',
            'availability_start' => 'required|date',
            'availability_end'   => 'required|date|after:availability_start',
            'condition'          => 'required|in:excellent,good,fair,poor',
            'status'             => 'required|in:available,unavailable',
            'photo'              => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            if ($resource->photo) {
                Storage::disk('public')->delete($resource->photo);
            }
            $data['photo'] = $request->file('photo')->store('resources', 'public');
        }

        $resource->update($data);
        return redirect()->route('resources.show', $resource)->with('success', 'Resource updated!');
    }

    public function destroy(Resource $resource)
    {
        $this->authorize('delete', $resource);
        if ($resource->photo) {
            Storage::disk('public')->delete($resource->photo);
        }
        $resource->delete();
        return redirect()->route('resources.index')->with('success', 'Resource deleted.');
    }

    // Feature 2: Store a review for a resource owner
    public function storeReview(Request $request, Resource $resource)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $alreadyReviewed = Review::where('resource_id', $resource->id)
            ->where('reviewer_id', Auth::id())
            ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'You have already reviewed this resource.');
        }

        if ($resource->owner_id === Auth::id()) {
            return back()->with('error', 'You cannot review your own resource.');
        }

        Review::create([
            'resource_id'  => $resource->id,
            'reviewer_id'  => Auth::id(),
            'reviewee_id'  => $resource->owner_id,
            'rating'       => $request->rating,
            'comment'      => $request->comment,
        ]);

        $resource->owner->updateCredibilityScore();

        return back()->with('success', 'Review submitted!');
    }
}
