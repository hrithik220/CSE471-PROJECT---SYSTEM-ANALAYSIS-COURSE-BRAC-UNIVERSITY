@extends('layouts.app')
@section('title', 'My Badges')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">🎖 Badge Collection</h1>
    <p class="text-gray-500 text-sm mt-1">
        You've earned <strong>{{ count($earnedSlugs) }}</strong> out of {{ $badges->count() }} badges
    </p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
    @foreach($badges as $badge)
        @php $earned = in_array($badge->slug, $earnedSlugs); @endphp
        <div class="bg-white rounded-2xl shadow-sm border-2 p-6 text-center transition {{ $earned ? 'border-yellow-400 hover:shadow-md' : 'border-gray-100 opacity-50 grayscale' }}">
            <div class="text-5xl mb-3">{{ $badge->icon }}</div>
            <h3 class="font-bold text-gray-800 mb-1" style="{{ $earned ? 'color:'.$badge->color : '' }}">
                {{ $badge->name }}
            </h3>
            <p class="text-xs text-gray-500 mb-3">{{ $badge->description }}</p>
            <div class="flex items-center justify-center gap-2">
                <span class="text-xs font-medium px-2 py-1 rounded-full {{ $earned ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-500' }}">
                    {{ number_format($badge->required_points) }} karma pts
                </span>
            </div>
            @if($earned)
                @php $userBadge = $user->badges->where('slug', $badge->slug)->first(); @endphp
                <p class="text-xs text-green-600 mt-2">
                    ✅ Earned {{ $userBadge?->pivot?->awarded_at ? \Carbon\Carbon::parse($userBadge->pivot->awarded_at)->format('d M Y') : '' }}
                </p>
            @else
                <p class="text-xs text-gray-400 mt-2">🔒 Locked</p>
            @endif
        </div>
    @endforeach
</div>

<div class="mt-8 text-center">
    <a href="{{ route('statistics.index') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-3 rounded-xl transition inline-block">
        📊 View My Statistics
    </a>
</div>
@endsection
