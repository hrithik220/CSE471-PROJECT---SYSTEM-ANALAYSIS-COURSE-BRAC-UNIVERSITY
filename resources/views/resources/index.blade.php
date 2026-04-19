@extends('layouts.app')
@section('title','Resources')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Browse Resources</h1>
        <p class="text-slate-500 text-sm mt-1">Find what you need from your campus community</p>
    </div>
    <a href="{{ route('resources.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Post Resource
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="bg-white rounded-2xl border border-slate-200 p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-[180px]">
        <label class="block text-xs font-medium text-slate-500 mb-1">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search resources..."
            class="w-full border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Type</label>
        <select name="type" class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Types</option>
            <option value="free_lending" {{ request('type') === 'free_lending' ? 'selected' : '' }}>Free Lending</option>
            <option value="exchange" {{ request('type') === 'exchange' ? 'selected' : '' }}>Exchange</option>
        </select>
    </div>
    <div>
        <label class="block text-xs font-medium text-slate-500 mb-1">Category</label>
        <select name="category" class="border border-slate-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Categories</option>
            @foreach(['Books','Electronics','Tools','Sports','Clothing','Other'] as $cat)
            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors">Filter</button>
    <a href="{{ route('resources.index') }}" class="text-slate-500 px-4 py-2 rounded-xl text-sm hover:bg-slate-100 transition-colors">Clear</a>
</form>

{{-- Grid --}}
@if($resources->count())
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
    @foreach($resources as $resource)
    <a href="{{ route('resources.show', $resource) }}" class="bg-white rounded-2xl border border-slate-200 overflow-hidden card-hover block">
        <div class="h-40 bg-slate-100 flex items-center justify-center">
            @if($resource->photo)
            <img src="{{ asset('storage/'.$resource->photo) }}" class="w-full h-full object-cover">
            @else
            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            @endif
        </div>
        <div class="p-4">
            <div class="flex items-start justify-between gap-2 mb-2">
                <h3 class="font-semibold text-slate-800 text-sm leading-snug">{{ $resource->title }}</h3>
                <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0 {{ $resource->type === 'free_lending' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                    {{ $resource->type === 'free_lending' ? 'Free' : 'Exchange' }}
                </span>
            </div>
            <p class="text-xs text-slate-500 mb-2 line-clamp-2">{{ $resource->description }}</p>
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400">{{ $resource->category }}</span>
                <div class="flex items-center gap-1">
                    <span class="text-yellow-500 text-xs">⭐</span>
                    <span class="text-xs font-medium text-slate-600">{{ $resource->owner->credibility_score }}</span>
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-1">by {{ $resource->owner->name }}</p>
        </div>
    </a>
    @endforeach
</div>
{{ $resources->links() }}
@else
<div class="bg-white rounded-2xl border border-slate-200 p-16 text-center">
    <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
    <p class="text-slate-400 font-medium">No resources found</p>
    <a href="{{ route('resources.create') }}" class="inline-block mt-3 text-blue-600 font-semibold hover:underline">Be the first to post!</a>
</div>
@endif
@endsection
