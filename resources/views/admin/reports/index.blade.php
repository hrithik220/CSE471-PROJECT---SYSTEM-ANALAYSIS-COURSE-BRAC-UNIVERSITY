@extends('layouts.app')
@section('title', 'Admin: Reports')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">⚙ Admin — Report Management</h1>
    <p class="text-gray-500 text-sm mt-1">Review, investigate and resolve community reports</p>
</div>

{{-- Stats Overview --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    @foreach(['pending'=>['🟡','Pending','yellow'],'under_review'=>['🔵','Under Review','blue'],'resolved'=>['🟢','Resolved','green'],'dismissed'=>['⚫','Dismissed','gray']] as $key => [$icon,$label,$color])
        <div class="bg-white rounded-xl shadow p-4 border-l-4 border-{{ $color }}-400 text-center">
            <div class="text-2xl">{{ $icon }}</div>
            <div class="text-2xl font-bold text-gray-800 mt-1">{{ $stats[$key] }}</div>
            <div class="text-xs text-gray-400">{{ $label }}</div>
        </div>
    @endforeach
</div>

{{-- Filters --}}
<form method="GET" class="flex gap-3 mb-5">
    <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        <option value="">All Statuses</option>
        @foreach(['pending','under_review','resolved','dismissed'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
        @endforeach
    </select>
    <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        <option value="">All Types</option>
        @foreach(['lost','damaged','unreturned'] as $t)
            <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
        @endforeach
    </select>
    <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">Filter</button>
    <a href="{{ route('admin.reports.index') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center">Clear</a>
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left">#</th>
                <th class="px-4 py-3 text-left">Resource</th>
                <th class="px-4 py-3 text-left">Reporter</th>
                <th class="px-4 py-3 text-left">Borrower</th>
                <th class="px-4 py-3 text-left">Type</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Filed</th>
                <th class="px-4 py-3 text-left">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($reports as $report)
                @php
                    $sc = ['pending'=>'bg-yellow-100 text-yellow-800','under_review'=>'bg-blue-100 text-blue-800','resolved'=>'bg-green-100 text-green-800','dismissed'=>'bg-gray-100 text-gray-500'];
                    $ti = ['lost'=>'🔍','damaged'=>'💥','unreturned'=>'⏳'];
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-gray-400">#{{ $report->id }}</td>
                    <td class="px-4 py-3 font-medium text-gray-800">{{ Str::limit($report->resource->title, 25) }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $report->reporter->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $report->borrower->name }}</td>
                    <td class="px-4 py-3">{{ $ti[$report->report_type] }} {{ ucfirst($report->report_type) }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sc[$report->status] }}">
                            {{ ucfirst(str_replace('_',' ',$report->status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs">{{ $report->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.reports.show', $report) }}"
                           class="text-indigo-600 hover:underline text-xs font-medium">Review →</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-10 text-center text-gray-400">No reports found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $reports->links() }}</div>
@endsection
