@extends('layouts.app')
@section('title', 'Edit Resource')
@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <a href="{{ route('resources.show', $resource) }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-blue-600 mb-6">
        ← Back to Resource
    </a>
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">✏️ Edit Resource</h1>
        <p class="text-sm text-gray-500 mb-6">Changes are visible immediately after saving.</p>

        <form action="{{ route('resources.update', $resource) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title', $resource->title) }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 @error('title') border-red-400 @enderror">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('description', $resource->description) }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id" class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $resource->category_id==$cat->id ? 'selected':'' }}>
                                {{ $cat->icon }} {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Condition</label>
                    <select name="condition" class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @foreach(['new'=>'🟢 New','good'=>'🔵 Good','fair'=>'🟡 Fair','poor'=>'🔴 Poor'] as $val=>$label)
                            <option value="{{ $val }}" {{ $resource->condition===$val ? 'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Availability Status</label>
                    <select name="availability_status" class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @foreach(['available'=>'✅ Available','borrowed'=>'🔄 Borrowed','unavailable'=>'❌ Unavailable'] as $val=>$label)
                            <option value="{{ $val }}" {{ $resource->availability_status===$val ? 'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Available Until</label>
                    <input type="date" name="availability_until" value="{{ old('availability_until', $resource->availability_until) }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            {{-- Pickup Location --}}
            <div class="bg-blue-50 rounded-xl p-4">
                <p class="text-sm font-semibold text-blue-800 mb-3">📍 Pickup Location</p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="pickup_address" value="{{ old('pickup_address', $resource->pickup_address) }}"
                           placeholder="e.g. DSC Building, Ground Floor, BRACU"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div class="grid grid-cols-2 gap-3 mt-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                        <input type="text" name="pickup_lat" value="{{ old('pickup_lat', $resource->pickup_lat) }}"
                               placeholder="23.7809"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                        <input type="text" name="pickup_lng" value="{{ old('pickup_lng', $resource->pickup_lng) }}"
                               placeholder="90.4012"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 font-semibold text-sm">
                    💾 Save Changes
                </button>
                <a href="{{ route('resources.show', $resource) }}"
                   class="flex-1 text-center bg-gray-100 text-gray-700 py-3 rounded-xl hover:bg-gray-200 font-semibold text-sm">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
