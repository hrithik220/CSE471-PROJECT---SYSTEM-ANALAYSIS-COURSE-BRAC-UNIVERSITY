@extends('layouts.app')
@section('title', 'Report #' . $report->id)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('reports.my') }}" class="text-gray-400 hover:text-gray-700">← Back</a>
        <h1 class="text-2xl font-bold text-gray-800">Report #{{ $report->id }}</h1>
        @php
            $statusColors = ['pending'=>'bg-yellow-100 text-yellow-800','under_review'=>'bg-blue-100 text-blue-800','resolved'=>'bg-green-100 text-green-800','dismissed'=>'bg-gray-100 text-gray-500'];
        @endphp
        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$report->status] }}">
            {{ ucfirst(str_replace('_',' ',$report->status)) }}
        </span>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 space-y-5">
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-400">Resource</span><p class="font-semibold mt-0.5">{{ $report->resource->title }}</p></div>
            <div><span class="text-gray-400">Report Type</span><p class="font-semibold mt-0.5">{{ ucfirst($report->report_type) }}</p></div>
            <div><span class="text-gray-400">Reporter</span><p class="font-semibold mt-0.5">{{ $report->reporter->name }}</p></div>
            <div><span class="text-gray-400">Borrower</span><p class="font-semibold mt-0.5">{{ $report->borrower->name }}</p></div>
            <div><span class="text-gray-400">Filed On</span><p class="font-semibold mt-0.5">{{ $report->created_at->format('d M Y, g:i A') }}</p></div>
            <div><span class="text-gray-400">Penalty</span>
                <p class="font-semibold mt-0.5 {{ $report->penalty_applied !== 'none' ? 'text-red-600' : 'text-gray-600' }}">
                    {{ ucfirst($report->penalty_applied) }}
                </p>
            </div>
        </div>

        <div>
            <p class="text-gray-400 text-sm mb-1">Description</p>
            <p class="text-gray-700 bg-gray-50 rounded-lg p-3 text-sm leading-relaxed">{{ $report->description }}</p>
        </div>

        @if($report->evidence_path)
            <div>
                <p class="text-gray-400 text-sm mb-2">Evidence</p>
                @if(str_ends_with($report->evidence_path, '.pdf'))
                    <a href="{{ Storage::url($report->evidence_path) }}" target="_blank"
                       class="text-indigo-600 hover:underline text-sm">📄 View PDF Evidence</a>
                @else
                    <img src="{{ Storage::url($report->evidence_path) }}" alt="Evidence"
                         class="rounded-xl max-h-64 object-cover border border-gray-200">
                @endif
            </div>
        @endif

        @if($report->admin_notes)
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <p class="text-sm font-medium text-blue-800 mb-1">📝 Admin Notes</p>
                <p class="text-sm text-blue-700">{{ $report->admin_notes }}</p>
                @if($report->reviewer)
                    <p class="text-xs text-blue-400 mt-1">Reviewed by {{ $report->reviewer->name }} on {{ $report->reviewed_at->format('d M Y') }}</p>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
