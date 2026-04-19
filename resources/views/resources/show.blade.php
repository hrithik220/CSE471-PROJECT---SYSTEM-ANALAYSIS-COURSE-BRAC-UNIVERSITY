@extends('layouts.app')
@section('title', $resource->title)
@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('resources.index') }}" class="text-slate-500 hover:text-slate-700 text-sm flex items-center gap-1 mb-5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Resources
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Resource Details --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                @if($resource->photo)
                <img src="{{ asset('storage/'.$resource->photo) }}" class="w-full h-64 object-cover">
                @else
                <div class="w-full h-48 bg-gradient-to-br from-blue-50 to-slate-100 flex items-center justify-center">
                    <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                </div>
                @endif
                <div class="p-6">
                    <div class="flex items-start justify-between gap-4 mb-3">
                        <h1 class="text-2xl font-bold text-slate-800">{{ $resource->title }}</h1>
                        <span class="text-sm px-3 py-1 rounded-full flex-shrink-0 {{ $resource->type === 'free_lending' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }} font-semibold">
                            {{ $resource->type === 'free_lending' ? '🎁 Free Lending' : '🔄 Exchange' }}
                        </span>
                    </div>
                    <p class="text-slate-600 leading-relaxed mb-4">{{ $resource->description }}</p>

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-slate-400 text-xs mb-0.5">Category</p>
                            <p class="font-semibold text-slate-700">{{ $resource->category }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-slate-400 text-xs mb-0.5">Condition</p>
                            <p class="font-semibold text-slate-700">{{ ucfirst($resource->condition) }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-slate-400 text-xs mb-0.5">Available From</p>
                            <p class="font-semibold text-slate-700">{{ $resource->availability_start->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-3">
                            <p class="text-slate-400 text-xs mb-0.5">Available Until</p>
                            <p class="font-semibold text-slate-700">{{ $resource->availability_end->format('M d, Y') }}</p>
                        </div>
                    </div>

                    @if($resource->location_name)
                    <div class="mt-3 bg-slate-50 rounded-xl p-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="text-sm text-slate-600">{{ $resource->location_name }}</span>
                    </div>
                    @endif

                    {{-- Owner actions --}}
                    @if(auth()->id() === $resource->owner_id)
                    <div class="flex gap-2 mt-5 pt-4 border-t border-slate-100">
                        <a href="{{ route('resources.edit', $resource) }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium px-4 py-2 rounded-xl text-sm transition-colors">Edit</a>
                        <form method="POST" action="{{ route('resources.destroy', $resource) }}" onsubmit="return confirm('Delete this resource?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-medium px-4 py-2 rounded-xl text-sm transition-colors">Delete</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Past Transaction History (Feature 2) --}}
            @if($resource->transactions->count())
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <h3 class="font-bold text-slate-800 mb-3">📋 Past Transactions</h3>
                <div class="space-y-2">
                    @foreach($resource->transactions as $tx)
                    <div class="flex items-center justify-between text-sm py-2 border-b border-slate-50 last:border-0">
                        <span class="text-slate-600">Borrowed by <span class="font-medium">{{ $tx->borrower->name }}</span></span>
                        <span class="text-slate-400">{{ $tx->returned_at ? $tx->returned_at->format('M d, Y') : '—' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Reviews (Feature 2) --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <h3 class="font-bold text-slate-800 mb-4">⭐ Reviews ({{ $resource->reviews->count() }})</h3>
                @forelse($resource->reviews as $review)
                <div class="border-b border-slate-100 pb-4 mb-4 last:border-0 last:mb-0">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-semibold text-slate-700 text-sm">{{ $review->reviewer->name }}</span>
                        <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    </div>
                    @if($review->comment)
                    <p class="text-slate-600 text-sm">{{ $review->comment }}</p>
                    @endif
                    <p class="text-xs text-slate-400 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                </div>
                @empty
                <p class="text-slate-400 text-sm">No reviews yet.</p>
                @endforelse

                {{-- Leave review --}}
                @if(!$alreadyReviewed && auth()->id() !== $resource->owner_id)
                <form method="POST" action="{{ route('resources.review.store', $resource) }}" class="mt-5 pt-4 border-t border-slate-100">
                    @csrf
                    <h4 class="font-semibold text-slate-700 mb-3 text-sm">Leave a Review</h4>
                    <div class="flex items-center gap-2 mb-3">
                        <label class="text-sm text-slate-600">Rating:</label>
                        <select name="rating" required class="border border-slate-200 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @for($i=5;$i>=1;$i--)<option value="{{ $i }}">{{ $i }} Star{{ $i>1?'s':'' }}</option>@endfor
                        </select>
                    </div>
                    <textarea name="comment" rows="2" placeholder="Share your experience..." class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none mb-2"></textarea>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">Submit Review</button>
                </form>
                @endif
            </div>
        </div>

        {{-- Right: Owner Card + Borrow Form --}}
        <div class="space-y-5">
            {{-- Owner Credibility (Feature 2) --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <h3 class="font-bold text-slate-800 mb-4">👤 Owner</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600">
                        {{ strtoupper(substr($resource->owner->name, 0, 2)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">{{ $resource->owner->name }}</p>
                        <p class="text-sm text-slate-500">{{ $resource->owner->campus ?? 'Campus' }}</p>
                    </div>
                </div>
                <div class="bg-yellow-50 rounded-xl p-4 text-center mb-3">
                    <p class="text-3xl font-bold text-yellow-600">{{ $resource->owner->credibility_score }}</p>
                    <p class="text-xs text-yellow-600 font-medium mt-1">Credibility Score</p>
                    <div class="flex justify-center gap-0.5 mt-1">
                        @for($i=1;$i<=5;$i++)
                        <span class="{{ $i <= round($resource->owner->credibility_score) ? 'text-yellow-400' : 'text-slate-200' }} text-lg">★</span>
                        @endfor
                    </div>
                </div>
                <div class="text-sm text-slate-600 space-y-1">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Total Reviews</span>
                        <span class="font-medium">{{ $resource->owner->reviewsReceived->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Resources Posted</span>
                        <span class="font-medium">{{ $resource->owner->resources->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- Borrow Request Form --}}
            @if($resource->status === 'available' && auth()->id() !== $resource->owner_id)
            <div class="bg-white rounded-2xl border border-blue-200 p-5">
                <h3 class="font-bold text-slate-800 mb-4">📬 Request to Borrow</h3>
                <form method="POST" action="{{ route('transactions.store') }}" class="space-y-3">
                    @csrf
                    <input type="hidden" name="resource_id" value="{{ $resource->id }}">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Return By *</label>
                        <input type="date" name="due_date" required min="{{ now()->addDay()->toDateString() }}"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    @if($resource->type === 'exchange')
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">What will you offer?</label>
                        <input type="text" name="exchange_item" placeholder="e.g. Physics Notes"
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    @endif
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Message (optional)</label>
                        <textarea name="message" rows="2" placeholder="Introduce yourself..."
                            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                        Send Request
                    </button>
                </form>
            </div>
            @elseif($resource->status !== 'available')
            <div class="bg-slate-100 rounded-2xl p-5 text-center">
                <p class="text-slate-500 font-medium">Currently Unavailable</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
