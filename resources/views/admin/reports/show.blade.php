@extends('layouts.app')
@section('title', 'Review Report #' . $report->id)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.reports.index') }}" class="text-gray-400 hover:text-gray-700">← All Reports</a>
        <h1 class="text-2xl font-bold text-gray-800">Review Report #{{ $report->id }}</h1>
    </div>

    {{-- Report Details --}}
    <div class="bg-white rounded-2xl shadow p-6 mb-5">
        <h2 class="font-semibold text-gray-700 mb-4">📋 Report Details</h2>
        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
            <div><span class="text-gray-400">Resource</span><p class="font-semibold mt-0.5">{{ $report->resource->title }}</p></div>
            <div><span class="text-gray-400">Report Type</span><p class="font-semibold mt-0.5">{{ ucfirst($report->report_type) }}</p></div>
            <div><span class="text-gray-400">Reporter</span><p class="font-semibold mt-0.5">{{ $report->reporter->name }}</p></div>
            <div><span class="text-gray-400">Borrower</span>
                <p class="font-semibold mt-0.5 text-red-700">{{ $report->transactioner->name }}</p>
            </div>
            <div><span class="text-gray-400">Filed On</span><p class="font-semibold mt-0.5">{{ $report->created_at->format('d M Y, g:i A') }}</p></div>
            <div><span class="text-gray-400">Borrow ID</span><p class="font-semibold mt-0.5">#{{ $report->transaction_id }}</p></div>
        </div>

        <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-700 leading-relaxed mb-4">
            <p class="text-gray-400 text-xs mb-1">Reporter's Description</p>
            {{ $report->description }}
        </div>

        @if($report->evidence_path)
            <div>
                <p class="text-gray-400 text-sm mb-2">Evidence</p>
                @if(str_ends_with($report->evidence_path, '.pdf'))
                    <a href="{{ Storage::url($report->evidence_path) }}" target="_blank" class="text-indigo-600 hover:underline text-sm">📄 View PDF</a>
                @else
                    <img src="{{ Storage::url($report->evidence_path) }}" class="rounded-xl max-h-64 object-cover border border-gray-200" alt="Evidence">
                @endif
            </div>
        @endif
    </div>

    {{-- Admin Review Form --}}
    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="font-semibold text-gray-700 mb-5">⚙ Admin Decision</h2>
        <form method="POST" action="{{ route('admin.reports.update', $report) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Update Status <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['under_review'=>['🔵','Under Review'],'resolved'=>['🟢','Resolved'],'dismissed'=>['⚫','Dismissed']] as $val => [$icon,$label])
                        <label class="cursor-pointer">
                            <input type="radio" name="status" value="{{ $val }}" class="sr-only peer"
                                   {{ old('status', $report->status) === $val ? 'checked' : '' }}>
                            <div class="border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 rounded-xl p-3 text-center transition text-sm font-medium">
                                {{ $icon }} {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Penalty for Borrower <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach(['none'=>['✅','None'],'warning'=>['⚠️','Warning'],'suspension'=>['🚫','Suspend 7d'],'ban'=>['🔒','Ban']] as $val => [$icon,$label])
                        <label class="cursor-pointer">
                            <input type="radio" name="penalty_applied" value="{{ $val }}" class="sr-only peer"
                                   {{ old('penalty_applied', $report->penalty_applied) === $val ? 'checked' : '' }}>
                            <div class="border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 rounded-xl p-3 text-center transition text-xs font-medium">
                                {{ $icon }}<br>{{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notes</label>
                <textarea name="admin_notes" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                          placeholder="Explain your decision (optional)...">{{ old('admin_notes', $report->admin_notes) }}</textarea>
            </div>

            <button type="submit"
                    class="w-full bg-indigo-700 hover:bg-indigo-800 text-white font-semibold py-3 rounded-xl transition">
                ✅ Submit Decision & Notify Both Parties
            </button>
        </form>
    </div>
</div>
@endsection
