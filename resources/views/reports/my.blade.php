@extends('layouts.app')
@section('title', 'My Reports')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">📋 My Reports</h1>
        <p class="text-gray-500 text-sm mt-1">Reports you've filed for lost, damaged, or unreturned items</p>
    </div>
    <a href="{{ route('reports.create') }}"
       class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition">
        + New Report
    </a>
</div>

@if($reports->isEmpty())
    <div class="bg-white rounded-2xl shadow p-16 text-center">
        <div class="text-6xl mb-4">✅</div>
        <p class="text-gray-500 text-lg">No reports filed yet. We hope it stays that way!</p>
    </div>
@else
    <div class="space-y-4">
        @foreach($reports as $report)
            @php
                $statusColors = [
                    'pending'      => 'bg-yellow-100 text-yellow-800',
                    'under_review' => 'bg-blue-100 text-blue-800',
                    'resolved'     => 'bg-green-100 text-green-800',
                    'dismissed'    => 'bg-gray-100 text-gray-600',
                ];
                $typeIcons = ['lost' => '🔍', 'damaged' => '💥', 'unreturned' => '⏳'];
            @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">{{ $typeIcons[$report->report_type] }}</span>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-gray-800">{{ $report->resource->title }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $statusColors[$report->status] }}">
                                    {{ ucfirst(str_replace('_',' ',$report->status)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mt-0.5">
                                Borrower: {{ $report->borrower->name }} &nbsp;|&nbsp;
                                Type: {{ ucfirst($report->report_type) }} &nbsp;|&nbsp;
                                Filed: {{ $report->created_at->format('d M Y') }}
                            </p>
                            @if($report->penalty_applied !== 'none')
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full mt-1 inline-block">
                                    Penalty: {{ ucfirst($report->penalty_applied) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('reports.show', $report) }}"
                       class="text-indigo-600 text-sm hover:underline shrink-0">View →</a>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $reports->links() }}</div>
@endif
@endsection
