@extends('layouts.app')
@section('title','Transactions')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">My Transactions</h1>
    <p class="text-slate-500 text-sm mt-1">Manage your borrowing and lending activity</p>
</div>

{{-- Tabs --}}
<div x-data="{ tab: 'borrowing' }" x-init="
    const params = new URLSearchParams(window.location.search);
    if (params.get('tab')) tab = params.get('tab');
">
    <div class="flex gap-2 mb-6 bg-white border border-slate-200 rounded-2xl p-1.5 w-fit">
        <button @click="tab='borrowing'" :class="tab==='borrowing' ? 'bg-blue-600 text-white shadow' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2 rounded-xl text-sm font-semibold transition-all">
            📦 Borrowing ({{ $borrowing->count() }})
        </button>
        <button @click="tab='lending'" :class="tab==='lending' ? 'bg-blue-600 text-white shadow' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2 rounded-xl text-sm font-semibold transition-all">
            🤝 Lending ({{ $lending->count() }})
        </button>
        <button @click="tab='requests'" :class="tab==='requests' ? 'bg-blue-600 text-white shadow' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2 rounded-xl text-sm font-semibold transition-all relative">
            🔔 Requests
            @if($pendingRequests->count())
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">{{ $pendingRequests->count() }}</span>
            @endif
        </button>
        <button @click="tab='history'" :class="tab==='history' ? 'bg-blue-600 text-white shadow' : 'text-slate-500 hover:text-slate-700'"
            class="px-5 py-2 rounded-xl text-sm font-semibold transition-all">
            🗂 History
        </button>
    </div>

    {{-- BORROWING --}}
    <div x-show="tab==='borrowing'">
        @if($borrowing->count())
        <div class="space-y-3">
            @foreach($borrowing as $tx)
            @php $overdue = $tx->isOverdue(); @endphp
            <div class="bg-white rounded-2xl border {{ $overdue ? 'border-red-300' : 'border-slate-200' }} p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    @if($tx->resource->photo)
                    <img src="{{ asset('storage/'.$tx->resource->photo) }}" class="w-12 h-12 object-cover rounded-xl">
                    @else
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('resources.show', $tx->resource) }}" class="font-semibold text-slate-800 hover:text-blue-600">{{ $tx->resource->title }}</a>
                    <p class="text-sm text-slate-500">from {{ $tx->lender->name }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold status-{{ $overdue ? 'overdue' : $tx->status }}">
                            {{ $overdue ? '🔴 OVERDUE' : ucfirst($tx->status) }}
                        </span>
                        <span class="text-xs text-slate-400">Due: <span class="{{ $overdue ? 'text-red-600 font-bold' : 'text-slate-600 font-medium' }}">{{ $tx->due_date->format('M d, Y') }}</span></span>
                    </div>
                </div>
                @if($overdue)
                <div class="text-red-500 text-xs font-bold">{{ $tx->due_date->diffForHumans() }}</div>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <p class="text-slate-400">You're not borrowing anything right now.</p>
            <a href="{{ route('resources.index') }}" class="inline-block mt-2 text-blue-600 font-semibold hover:underline text-sm">Browse resources →</a>
        </div>
        @endif
    </div>

    {{-- LENDING --}}
    <div x-show="tab==='lending'">
        @if($lending->count())
        <div class="space-y-3">
            @foreach($lending as $tx)
            @php $overdue = $tx->isOverdue(); @endphp
            <div class="bg-white rounded-2xl border {{ $overdue ? 'border-red-300' : 'border-slate-200' }} p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    @if($tx->resource->photo)
                    <img src="{{ asset('storage/'.$tx->resource->photo) }}" class="w-12 h-12 object-cover rounded-xl">
                    @else
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('resources.show', $tx->resource) }}" class="font-semibold text-slate-800 hover:text-blue-600">{{ $tx->resource->title }}</a>
                    <p class="text-sm text-slate-500">to {{ $tx->borrower->name }}</p>
                    @if($tx->message)<p class="text-xs text-slate-400 italic mt-0.5">"{{ $tx->message }}"</p>@endif
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold status-{{ $overdue ? 'overdue' : $tx->status }}">
                            {{ $overdue ? '🔴 OVERDUE' : ucfirst($tx->status) }}
                        </span>
                        <span class="text-xs text-slate-400">Due: {{ $tx->due_date->format('M d, Y') }}</span>
                    </div>
                </div>
                @if(in_array($tx->status, ['active','overdue']))
                <form method="POST" action="{{ route('transactions.return', $tx) }}">
                    @csrf @method('PUT')
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-3 py-2 rounded-xl transition-colors">Mark Returned</button>
                </form>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <p class="text-slate-400">You're not lending anything right now.</p>
        </div>
        @endif
    </div>

    {{-- PENDING REQUESTS --}}
    <div x-show="tab==='requests'">
        @if($pendingRequests->count())
        <div class="space-y-3">
            @foreach($pendingRequests as $tx)
            <div class="bg-white rounded-2xl border border-yellow-200 bg-yellow-50/30 p-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center font-bold text-yellow-700 flex-shrink-0 text-sm">
                        {{ strtoupper(substr($tx->borrower->name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-slate-800">{{ $tx->borrower->name }} <span class="font-normal text-slate-500">wants to borrow</span> {{ $tx->resource->title }}</p>
                        <p class="text-sm text-slate-500 mt-0.5">Return by: <strong>{{ $tx->due_date->format('M d, Y') }}</strong></p>
                        @if($tx->exchange_item)<p class="text-sm text-slate-500">Offering: <strong>{{ $tx->exchange_item }}</strong></p>@endif
                        @if($tx->message)<p class="text-sm text-slate-500 italic mt-1">"{{ $tx->message }}"</p>@endif
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <form method="POST" action="{{ route('transactions.approve', $tx) }}">
                            @csrf @method('PUT')
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors">✓ Approve</button>
                        </form>
                        <form method="POST" action="{{ route('transactions.reject', $tx) }}">
                            @csrf @method('PUT')
                            <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 text-sm font-semibold px-4 py-2 rounded-xl transition-colors">✗ Reject</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <p class="text-slate-400">No pending requests.</p>
        </div>
        @endif
    </div>

    {{-- HISTORY --}}
    <div x-show="tab==='history'">
        @if($history->count())
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600">Resource</th>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600">Role</th>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600">With</th>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600">Status</th>
                        <th class="text-left px-5 py-3 font-semibold text-slate-600">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($history as $tx)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-medium text-slate-800">{{ $tx->resource->title }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $tx->borrower_id === auth()->id() ? 'Borrowed' : 'Lent' }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $tx->borrower_id === auth()->id() ? $tx->lender->name : $tx->borrower->name }}</td>
                        <td class="px-5 py-3"><span class="px-2.5 py-1 rounded-full text-xs font-semibold status-{{ $tx->status }}">{{ ucfirst($tx->status) }}</span></td>
                        <td class="px-5 py-3 text-slate-400">{{ $tx->updated_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <p class="text-slate-400">No transaction history yet.</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@endsection
