<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CampusShare')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-brand { background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%); }
        .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
        .status-pending  { background:#fef9c3; color:#854d0e; }
        .status-active   { background:#dcfce7; color:#166534; }
        .status-overdue  { background:#fee2e2; color:#991b1b; }
        .status-returned { background:#f1f5f9; color:#475569; }
        .status-rejected { background:#fee2e2; color:#991b1b; }
        .status-approved { background:#dbeafe; color:#1e40af; }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 10px;
            color: #64748b;
            transition: all .2s;
            font-weight: 500;
            text-decoration: none;
        }
        .sidebar-link:hover, .sidebar-link.active { background: #eff6ff; color: #2563eb; }
        .sidebar-link svg { width: 20px; height: 20px; flex-shrink: 0; }
        .nav-section {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #94a3b8;
            padding: 10px 16px 4px;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 min-h-screen">
<div class="flex min-h-screen">

    {{-- ── SIDEBAR ──────────────────────────────────────────────────────── --}}
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col fixed top-0 left-0 h-full z-30 overflow-y-auto">

        {{-- Logo --}}
        <div class="p-6 border-b border-slate-100">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 gradient-brand rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="font-bold text-slate-800 text-lg">CampusShare</span>
            </a>
        </div>

        <nav class="flex-1 p-4 space-y-1">

            {{-- MAIN --}}
            <p class="nav-section">Main</p>
            <a href="{{ route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            {{-- RESOURCES --}}
            <p class="nav-section">Resources</p>
            <a href="{{ route('resources.index') }}"
               class="sidebar-link {{ request()->routeIs('resources.index') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Browse Resources
            </a>
            <a href="{{ route('resources.create') }}"
               class="sidebar-link {{ request()->routeIs('resources.create') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Post Resource
            </a>

            {{-- ACTIVITY --}}
            <p class="nav-section">Activity</p>
            <a href="{{ route('transactions.index') }}"
               class="sidebar-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                Transactions
            </a>
            <a href="{{ route('map.index') }}"
               class="sidebar-link {{ request()->routeIs('map.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                </svg>
                Campus Map
            </a>

            {{-- COMMUNITY (Module 3) --}}
            <p class="nav-section">Community</p>

            {{-- Feature 1: Reminders --}}
            <a href="{{ route('reminders.notifications') }}"
               class="sidebar-link {{ request()->routeIs('reminders.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                Reminders
                @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                @if($unread > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">
                        {{ $unread }}
                    </span>
                @endif
            </a>

            {{-- Feature 2: Statistics --}}
            <a href="{{ route('statistics.index') }}"
               class="sidebar-link {{ request()->routeIs('statistics.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                My Statistics
            </a>

            {{-- Feature 4: Leaderboard --}}
            <a href="{{ route('leaderboard.index') }}"
               class="sidebar-link {{ request()->routeIs('leaderboard.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Leaderboard
            </a>

            {{-- Feature 3: Report Issue --}}
            <a href="{{ route('reports.create') }}"
               class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Report Issue
            </a>

            {{-- ADMIN PANEL --}}
            @if(auth()->user()->role === 'admin')
            <p class="nav-section">Admin Panel</p>
            <a href="{{ route('admin.reports.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Manage Reports
            </a>
            @endif

        </nav>

        {{-- User footer --}}
        <div class="p-4 border-t border-slate-100">
            <div class="flex items-center gap-3 mb-3 px-2">
                <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600 text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-800 text-sm truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500">⭐ {{ auth()->user()->credibility_score }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="sidebar-link w-full text-left text-red-500 hover:bg-red-50 hover:text-red-600">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ── MAIN CONTENT ──────────────────────────────────────────────────── --}}
    <main class="flex-1 ml-64 p-8">
        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-2">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>

</div>
@stack('scripts')
</body>
</html>
