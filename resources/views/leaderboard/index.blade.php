@extends('layouts.app')
@section('title', 'Leaderboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">🏆 Monthly Leaderboard</h1>
    <p class="text-gray-500 text-sm mt-1">Top contributors based on lending, reviews & engagement</p>
</div>

{{-- Month/Year Filter --}}
<form method="GET" class="flex gap-3 mb-6">
    <select name="month" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        @foreach($months as $m)
            <option value="{{ $m['value'] }}" {{ $m['value'] == $month ? 'selected' : '' }}>{{ $m['label'] }}</option>
        @endforeach
    </select>
    <select name="year" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        @foreach([date('Y'), date('Y')-1] as $y)
            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
        @endforeach
    </select>
    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">Filter</button>
</form>

{{-- My Rank Card --}}
@if($myEntry)
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-2xl p-5 mb-6 flex items-center justify-between">
        <div>
            <p class="text-indigo-200 text-sm">Your Rank This Month</p>
            <p class="text-4xl font-bold mt-1">#{{ $myEntry->rank ?: '–' }}</p>
        </div>
        <div class="text-right">
            <p class="text-indigo-200 text-sm">Total Points</p>
            <p class="text-3xl font-bold">{{ number_format($myEntry->total_points) }}</p>
            <p class="text-indigo-200 text-xs mt-1">
                {{ $myEntry->lending_count }} lends · {{ $myEntry->positive_reviews }} reviews
            </p>
        </div>
    </div>
@endif

{{-- Top 3 Podium --}}
@php $top3 = $entries->take(3); @endphp
@if($top3->count() >= 3)
    <div class="grid grid-cols-3 gap-4 mb-8">
        @foreach([1,0,2] as $podiumPos)
            @php $entry = $top3->values()->get($podiumPos); @endphp
            @if($entry)
                @php
                    $podiumH = ['h-28','h-36','h-20'];
                    $medals  = ['🥇','🥈','🥉'];
                    $colors  = ['bg-yellow-100 border-yellow-400','bg-indigo-100 border-indigo-500','bg-orange-100 border-orange-400'];
                @endphp
                <div class="flex flex-col items-center">
                    <div class="text-3xl mb-1">{{ $medals[$podiumPos] }}</div>
                    <div class="w-14 h-14 rounded-full bg-indigo-200 flex items-center justify-center text-xl font-bold text-indigo-700 mb-1">
                        {{ strtoupper(substr($entry->name, 0, 1)) }}
                    </div>
                    <p class="text-xs font-semibold text-center text-gray-700 truncate w-full text-center">{{ $entry->name }}</p>
                    <p class="text-sm font-bold text-indigo-600">{{ number_format($entry->total_points) }} pts</p>
                    <div class="{{ $podiumH[$podiumPos] }} w-full rounded-t-xl mt-2 border-t-2 {{ $colors[$podiumPos] }}"></div>
                </div>
            @endif
        @endforeach
    </div>
@endif

{{-- Full Rankings Table --}}
<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Rank</th>
                <th class="px-4 py-3 text-left">Member</th>
                <th class="px-4 py-3 text-right">Lends</th>
                <th class="px-4 py-3 text-right">Reviews</th>
                <th class="px-4 py-3 text-right">Points</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($entries as $entry)
                <tr class="{{ $entry->id === auth()->id() ? 'bg-indigo-50' : 'hover:bg-gray-50' }} transition">
                    <td class="px-4 py-3 font-bold text-gray-700">
                        @if($entry->rank <= 3)
                            {{ ['1'=>'🥇','2'=>'🥈','3'=>'🥉'][$entry->rank] }}
                        @else
                            #{{ $entry->rank }}
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-indigo-200 flex items-center justify-center text-xs font-bold text-indigo-700">
                                {{ strtoupper(substr($entry->name, 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-800">
                                {{ $entry->name }}
                                @if($entry->id === auth()->id())
                                    <span class="text-xs text-indigo-500">(you)</span>
                                @endif
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-right text-gray-600">{{ $entry->lending_count }}</td>
                    <td class="px-4 py-3 text-right text-gray-600">{{ $entry->positive_reviews }}</td>
                    <td class="px-4 py-3 text-right font-bold text-indigo-700">{{ number_format($entry->total_points) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-gray-400">No entries for this month yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Badge Legend --}}
<div class="mt-8 bg-white rounded-2xl shadow p-6">
    <h2 class="font-semibold text-gray-700 mb-4">🎖 Badge Milestones</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        @foreach($badges as $badge)
            <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-3">
                <span class="text-2xl">{{ $badge->icon }}</span>
                <div>
                    <p class="text-sm font-semibold" style="color: {{ $badge->color }}">{{ $badge->name }}</p>
                    <p class="text-xs text-gray-400">{{ number_format($badge->required_points) }} pts</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection