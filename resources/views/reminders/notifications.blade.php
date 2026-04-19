@extends('layouts.app')
@section('title', 'My Notifications')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">🔔 Notifications</h1>
        <p class="text-gray-500 text-sm mt-1">Due-date reminders and community alerts</p>
    </div>
    @if($notifications->total() > 0)
        <form method="POST" action="{{ route('reminders.read-all') }}">
            @csrf
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">
                ✓ Mark all as read
            </button>
        </form>
    @endif
</div>

@if($notifications->isEmpty())
    <div class="bg-white rounded-2xl shadow p-16 text-center">
        <div class="text-6xl mb-4">🎉</div>
        <p class="text-gray-500 text-lg">You're all caught up! No notifications.</p>
    </div>
@else
    <div class="space-y-3">
        @foreach($notifications as $notif)
            @php $data = $notif->data; @endphp
            <div class="bg-white rounded-xl shadow-sm border {{ $notif->read_at ? 'border-gray-100' : 'border-indigo-300 bg-indigo-50' }} p-4 flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="text-2xl mt-0.5">
                        @if($data['type'] === 'due_date_reminder') ⏰
                        @elseif($data['type'] === 'badge_awarded') 🏆
                        @elseif($data['type'] === 'report_status') 📋
                        @else 🔔
                        @endif
                    </span>
                    <div>
                        <p class="font-medium text-gray-800">{{ $data['message'] ?? 'You have a new notification.' }}</p>
                        @if(isset($data['due_date']))
                            <p class="text-sm text-gray-500 mt-0.5">Due: {{ \Carbon\Carbon::parse($data['due_date'])->format('d M Y') }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @if(!$notif->read_at)
                    <form method="POST" action="{{ route('reminders.read', $notif->id) }}" class="shrink-0">
                        @csrf
                        <button class="text-xs text-indigo-600 hover:underline whitespace-nowrap">Mark read</button>
                    </form>
                @else
                    <span class="text-xs text-gray-400 shrink-0">Read</span>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $notifications->links() }}</div>
@endif
@endsection
