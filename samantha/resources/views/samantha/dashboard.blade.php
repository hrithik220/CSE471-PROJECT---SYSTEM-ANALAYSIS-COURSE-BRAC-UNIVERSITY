{{-- Samantha | Module 2: Lending & Borrowing Dashboard --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Transactions – CampusShare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0d0f14;
            --surface: #161920;
            --surface2: #1e222e;
            --border: #2a2f3e;
            --accent: #f0c040;
            --accent2: #e07b39;
            --text: #e8eaf0;
            --muted: #6b7280;
            --success: #34d399;
            --danger: #f87171;
            --warning: #fbbf24;
            --info: #60a5fa;
            --purple: #a78bfa;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
        }
        h1,h2,h3,.font-display { font-family: 'Syne', sans-serif; }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        .navbar {
            background: rgba(13,15,20,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .page-wrapper {
            position: relative;
            z-index: 1;
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }

        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(240,192,64,0.12);
            border: 1px solid rgba(240,192,64,0.3);
            color: var(--accent);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 4px 12px;
            border-radius: 99px;
            margin-bottom: 16px;
        }

        /* Stats row */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin: 36px 0 32px;
        }
        @media (max-width: 768px) { .stats-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px 22px;
            transition: border-color 0.2s, transform 0.2s;
        }
        .stat-card:hover { border-color: rgba(240,192,64,0.3); transform: translateY(-2px); }
        .stat-card .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--muted);
            font-weight: 600;
            margin-bottom: 8px;
        }
        .stat-card .value {
            font-family: 'Syne', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }
        .stat-card .sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 4px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 4px;
            margin-bottom: 24px;
            width: fit-content;
        }
        .tab-btn {
            padding: 9px 20px;
            border-radius: 9px;
            font-family: 'Syne', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            background: transparent;
            color: var(--muted);
            transition: all 0.18s;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .tab-btn.active {
            background: var(--accent);
            color: #0d0f14;
        }
        .tab-badge {
            background: rgba(255,255,255,0.15);
            border-radius: 99px;
            padding: 1px 7px;
            font-size: 11px;
        }
        .tab-btn.active .tab-badge { background: rgba(13,15,20,0.2); }

        /* Table */
        .table-wrapper {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }
        .table-header {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .table-title {
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
        }

        table { width: 100%; border-collapse: collapse; }
        thead tr { border-bottom: 1px solid var(--border); }
        thead th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--muted);
            font-weight: 600;
            padding: 14px 20px;
        }
        tbody tr {
            border-bottom: 1px solid rgba(42,47,62,0.6);
            transition: background 0.15s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(255,255,255,0.02); }
        tbody td { padding: 16px 20px; font-size: 14px; vertical-align: middle; }

        /* Status badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge.active { background: rgba(52,211,153,0.12); color: var(--success); border: 1px solid rgba(52,211,153,0.3); }
        .badge.active .badge-dot { background: var(--success); }
        .badge.pending { background: rgba(251,191,36,0.12); color: var(--warning); border: 1px solid rgba(251,191,36,0.3); }
        .badge.pending .badge-dot { background: var(--warning); animation: pulse 1.5s infinite; }
        .badge.overdue { background: rgba(248,113,113,0.12); color: var(--danger); border: 1px solid rgba(248,113,113,0.3); }
        .badge.overdue .badge-dot { background: var(--danger); animation: pulse 1s infinite; }
        .badge.returned { background: rgba(96,165,250,0.1); color: var(--info); border: 1px solid rgba(96,165,250,0.25); }
        .badge.returned .badge-dot { background: var(--info); }
        .badge.rejected { background: rgba(107,114,128,0.1); color: var(--muted); border: 1px solid var(--border); }
        .badge.rejected .badge-dot { background: var(--muted); }

        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

        /* Due date */
        .due-normal { color: var(--text); font-size: 13px; }
        .due-soon { color: var(--warning); font-size: 13px; font-weight: 500; }
        .due-overdue { color: var(--danger); font-size: 13px; font-weight: 600; }
        .days-left { font-size: 11px; opacity: 0.7; margin-top: 2px; }

        /* Resource name */
        .resource-name { font-weight: 500; color: var(--text); }
        .resource-sub { font-size: 12px; color: var(--muted); margin-top: 2px; }

        /* Actions */
        .action-btn {
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.18s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-approve { background: rgba(52,211,153,0.12); color: var(--success); border: 1px solid rgba(52,211,153,0.3); }
        .btn-approve:hover { background: rgba(52,211,153,0.2); }
        .btn-reject { background: rgba(248,113,113,0.1); color: var(--danger); border: 1px solid rgba(248,113,113,0.25); }
        .btn-reject:hover { background: rgba(248,113,113,0.18); }
        .btn-remind { background: rgba(240,192,64,0.1); color: var(--accent); border: 1px solid rgba(240,192,64,0.25); }
        .btn-remind:hover { background: rgba(240,192,64,0.18); }
        .btn-view { background: var(--surface2); color: var(--muted); border: 1px solid var(--border); }
        .btn-view:hover { color: var(--text); }

        /* Person cell */
        .person-cell { display: flex; align-items: center; gap: 10px; }
        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            font-weight: 800;
            flex-shrink: 0;
        }
        .person-name { font-size: 13px; font-weight: 500; }
        .person-score { font-size: 11px; color: var(--muted); }

        /* Overdue alert */
        .overdue-alert {
            background: rgba(248,113,113,0.08);
            border: 1px solid rgba(248,113,113,0.25);
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: var(--danger);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 24px;
            color: var(--muted);
        }
        .empty-state .icon { font-size: 40px; margin-bottom: 12px; }
        .empty-state p { font-size: 14px; }

        /* Search & filter bar */
        .filter-bar {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        .filter-input {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 13px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            outline: none;
            width: 200px;
            transition: border-color 0.2s;
        }
        .filter-input:focus { border-color: var(--accent); }
        .filter-select {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 13px;
            color: var(--muted);
            font-family: 'DM Sans', sans-serif;
            outline: none;
            appearance: none;
            cursor: pointer;
        }

        /* Tab panels */
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* Responsive table */
        @media (max-width: 700px) {
            thead { display: none; }
            tbody tr { display: block; padding: 16px; border-bottom: 1px solid var(--border); }
            tbody td { display: flex; justify-content: space-between; align-items: center; padding: 6px 0; font-size: 13px; }
            tbody td::before { content: attr(data-label); color: var(--muted); font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; }
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:60px;">
        <a href="/" style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.2rem;color:var(--text);text-decoration:none;">
            Campus<span style="color:var(--accent);">Share</span>
        </a>
        <div style="display:flex;align-items:center;gap:20px;">
            <a href="{{ route('resources.create') }}" style="background:var(--accent);color:#0d0f14;padding:7px 16px;border-radius:8px;font-family:'Syne',sans-serif;font-size:12px;font-weight:700;text-decoration:none;">+ Post Resource</a>
            <div style="width:32px;height:32px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;color:#0d0f14;font-family:'Syne',sans-serif;font-weight:800;font-size:13px;">
                {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
            </div>
        </div>
    </div>
</nav>

<div class="page-wrapper">
    {{-- Header --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div>
            <div class="section-tag">
                <svg width="8" height="8" viewBox="0 0 8 8" fill="currentColor"><circle cx="4" cy="4" r="4"/></svg>
                Module 2 · Samantha
            </div>
            <h1 style="font-family:'Syne',sans-serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:800;line-height:1.1;">
                My <span style="color:var(--accent);">Transactions</span>
            </h1>
            <p style="color:var(--muted);font-size:14px;margin-top:6px;">Track everything you're lending and borrowing.</p>
        </div>
        <div style="text-align:right;">
            <div style="font-size:12px;color:var(--muted);">Last updated</div>
            <div style="font-family:'Syne',sans-serif;font-weight:700;color:var(--text);font-size:14px;">{{ now()->format('d M Y, H:i') }}</div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="label">Active Lendings</div>
            <div class="value" style="color:var(--success);">{{ $stats['active_lendings'] ?? 3 }}</div>
            <div class="sub">items out with borrowers</div>
        </div>
        <div class="stat-card">
            <div class="label">Active Borrowings</div>
            <div class="value" style="color:var(--info);">{{ $stats['active_borrowings'] ?? 2 }}</div>
            <div class="sub">items you currently hold</div>
        </div>
        <div class="stat-card">
            <div class="label">Pending Requests</div>
            <div class="value" style="color:var(--warning);">{{ $stats['pending_requests'] ?? 4 }}</div>
            <div class="sub">awaiting your response</div>
        </div>
        <div class="stat-card">
            <div class="label">Overdue Items</div>
            <div class="value" style="color:{{ ($stats['overdue'] ?? 1) > 0 ? 'var(--danger)' : 'var(--success)' }};">{{ $stats['overdue'] ?? 1 }}</div>
            <div class="sub">past return date</div>
        </div>
    </div>

    {{-- Overdue Alert --}}
    @if(($stats['overdue'] ?? 1) > 0)
    <div class="overdue-alert">
        <span style="font-size:20px;">⚠️</span>
        <div>
            <strong>{{ $stats['overdue'] ?? 1 }} item(s) overdue.</strong>
            Borrowers have been automatically notified via SMS. You can also send a manual reminder below.
        </div>
    </div>
    @endif

    {{-- Tabs --}}
    <div class="tabs">
        <button class="tab-btn active" onclick="switchTab('lending', this)">
            📤 Lending
            <span class="tab-badge">{{ ($activelendings ?? collect())->count() ?: 3 }}</span>
        </button>
        <button class="tab-btn" onclick="switchTab('borrowing', this)">
            📥 Borrowing
            <span class="tab-badge">{{ ($activeBorrowings ?? collect())->count() ?: 2 }}</span>
        </button>
        <button class="tab-btn" onclick="switchTab('pending', this)">
            ⏳ Pending
            <span class="tab-badge">{{ ($pendingRequests ?? collect())->count() ?: 4 }}</span>
        </button>
        <button class="tab-btn" onclick="switchTab('history', this)">
            🗂 History
        </button>
    </div>

    {{-- ========== TAB: LENDING ========== --}}
    <div id="tab-lending" class="tab-panel active">
        <div class="table-wrapper">
            <div class="table-header">
                <div class="table-title">Items You're Lending</div>
                <div class="filter-bar">
                    <input type="text" class="filter-input" placeholder="🔍 Search resource..." oninput="filterTable('lending-table', this.value)">
                    <select class="filter-select" onchange="filterByStatus('lending-table', this.value)">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="overdue">Overdue</option>
                        <option value="returned">Returned</option>
                    </select>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table id="lending-table">
                    <thead>
                        <tr>
                            <th>Resource</th>
                            <th>Borrower</th>
                            <th>Issued On</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activelendings ?? [] as $t)
                        <tr>
                            <td data-label="Resource">
                                <div class="resource-name">{{ $t->resource->title }}</div>
                                <div class="resource-sub">{{ ucfirst($t->resource->category) }}</div>
                            </td>
                            <td data-label="Borrower">
                                <div class="person-cell">
                                    <div class="avatar" style="background:rgba(96,165,250,0.15);color:var(--info);">{{ strtoupper(substr($t->borrower->name,0,1)) }}</div>
                                    <div>
                                        <div class="person-name">{{ $t->borrower->name }}</div>
                                        <div class="person-score">⭐ {{ $t->borrower->credibility_score ?? '4.2' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Issued">{{ $t->issued_at->format('d M Y') }}</td>
                            <td data-label="Due Date">
                                @php $daysLeft = now()->diffInDays($t->due_date, false); @endphp
                                <div class="{{ $daysLeft < 0 ? 'due-overdue' : ($daysLeft <= 2 ? 'due-soon' : 'due-normal') }}">
                                    {{ $t->due_date->format('d M Y') }}
                                </div>
                                <div class="days-left">
                                    {{ $daysLeft < 0 ? abs($daysLeft).' days overdue' : $daysLeft.' days left' }}
                                </div>
                            </td>
                            <td data-label="Status">
                                @if($daysLeft < 0)
                                    <span class="badge overdue"><span class="badge-dot"></span>Overdue</span>
                                @elseif($t->status === 'returned')
                                    <span class="badge returned"><span class="badge-dot"></span>Returned</span>
                                @else
                                    <span class="badge active"><span class="badge-dot"></span>Active</span>
                                @endif
                            </td>
                            <td data-label="Actions">
                                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                    @if($daysLeft < 0)
                                        <form action="{{ route('transactions.remind', $t->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="action-btn btn-remind">📱 Remind</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('transactions.show', $t->id) }}" class="action-btn btn-view">View</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        {{-- Demo rows for frontend preview --}}
                        <tr>
                            <td data-label="Resource">
                                <div class="resource-name">Engineering Physics – Halliday</div>
                                <div class="resource-sub">Textbook</div>
                            </td>
                            <td data-label="Borrower">
                                <div class="person-cell">
                                    <div class="avatar" style="background:rgba(96,165,250,0.15);color:var(--info);">R</div>
                                    <div>
                                        <div class="person-name">Rafiq Ahmed</div>
                                        <div class="person-score">⭐ 4.5</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Issued">01 Apr 2026</td>
                            <td data-label="Due Date">
                                <div class="due-overdue">09 Apr 2026</div>
                                <div class="days-left">4 days overdue</div>
                            </td>
                            <td data-label="Status"><span class="badge overdue"><span class="badge-dot"></span>Overdue</span></td>
                            <td data-label="Actions">
                                <div style="display:flex;gap:6px;">
                                    <button class="action-btn btn-remind" onclick="showToast('SMS reminder sent to Rafiq!')">📱 Remind</button>
                                    <button class="action-btn btn-view">View</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Resource">
                                <div class="resource-name">Arduino Starter Kit</div>
                                <div class="resource-sub">Electronics</div>
                            </td>
                            <td data-label="Borrower">
                                <div class="person-cell">
                                    <div class="avatar" style="background:rgba(167,139,250,0.15);color:var(--purple);">N</div>
                                    <div>
                                        <div class="person-name">Nadia Islam</div>
                                        <div class="person-score">⭐ 4.8</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Issued">05 Apr 2026</td>
                            <td data-label="Due Date">
                                <div class="due-soon">15 Apr 2026</div>
                                <div class="days-left">2 days left</div>
                            </td>
                            <td data-label="Status"><span class="badge active"><span class="badge-dot"></span>Active</span></td>
                            <td data-label="Actions">
                                <div style="display:flex;gap:6px;">
                                    <button class="action-btn btn-view">View</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Resource">
                                <div class="resource-name">Data Structures – Cormen</div>
                                <div class="resource-sub">Textbook</div>
                            </td>
                            <td data-label="Borrower">
                                <div class="person-cell">
                                    <div class="avatar" style="background:rgba(52,211,153,0.15);color:var(--success);">T</div>
                                    <div>
                                        <div class="person-name">Tanvir Hossain</div>
                                        <div class="person-score">⭐ 3.9</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Issued">20 Mar 2026</td>
                            <td data-label="Due Date">
                                <div class="due-normal">20 May 2026</div>
                                <div class="days-left">37 days left</div>
                            </td>
                            <td data-label="Status"><span class="badge active"><span class="badge-dot"></span>Active</span></td>
                            <td data-label="Actions">
                                <div style="display:flex;gap:6px;">
                                    <button class="action-btn btn-view">View</button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========== TAB: BORROWING ========== --}}
    <div id="tab-borrowing" class="tab-panel">
        <div class="table-wrapper">
            <div class="table-header">
                <div class="table-title">Items You're Borrowing</div>
                <div class="filter-bar">
                    <input type="text" class="filter-input" placeholder="🔍 Search..." oninput="filterTable('borrowing-table', this.value)">
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table id="borrowing-table">
                    <thead>
                        <tr>
                            <th>Resource</th>
                            <th>Owner</th>
                            <th>Borrowed On</th>
                            <th>Return By</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeBorrowings ?? [] as $t)
                        <tr>
                            <td data-label="Resource">
                                <div class="resource-name">{{ $t->resource->title }}</div>
                                <div class="resource-sub">{{ ucfirst($t->resource->category) }}</div>
                            </td>
                            <td data-label="Owner">
                                <div class="person-cell">
                                    <div class="avatar" style="background:rgba(240,192,64,0.15);color:var(--accent);">{{ strtoupper(substr($t->owner->name,0,1)) }}</div>
                                    <div>
                                        <div class="person-name">{{ $t->owner->name }}</div>
                                        <div class="person-score">⭐ {{ $t->owner->credibility_score ?? '4.7' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Borrowed On">{{ $t->issued_at->format('d M Y') }}</td>
                            <td data-label="Return By">
                                @php $daysLeft = now()->diffInDays($t->due_date, false); @endphp
                                <div class="{{ $daysLeft < 0 ? 'due-overdue' : ($daysLeft <= 2 ? 'due-soon' : 'due-normal') }}">
                                    {{ $t->due_date->format('d M Y') }}
                                </div>
                                <div class="days-left">
                                    {{ $daysLeft < 0 ? abs($daysLeft).' days overdue' : $daysLeft.' days left' }}
                                </div>
                            </td>
                            <td data-label="Status">
                                @if($daysLeft < 0)
                                    <span class="badge overdue"><span class="badge-dot"></span>Overdue</span>
                                @else
                                    <span class="badge active"><span class="badge-dot"></span>Active</span>
                                @endif
                            </td>
                            <td data-label="Actions">
                                <form action="{{ route('transactions.return', $t->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="action-btn btn-approve">✓ Mark Returned</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td data-label="Resource">
                                <div class="resource-name">Oscilloscope – DSO138</div>
                                <div class="resource-sub">Lab Equipment</div>
                            </td>
                            <td data-label="Owner">
                                <div class="person-cell">
                                    <div class="avatar" style="background:rgba(240,192,64,0.15);color:var(--accent);">F</div>
                                    <div>
                                        <div class="person-name">Fahim Rahman</div>
                                        <div class="person-score">⭐ 4.7</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Borrowed On">10 Apr 2026</td>
                            <td data-label="Return By">
                                <div class="due-soon">17 Apr 2026</div>
                                <div class="days-left">4 days left</div>
                            </td>
                            <td data-label="Status"><span class="badge active"><span class="badge-dot"></span>Active</span></td>
                            <td data-label="Actions">
                                <button class="action-btn btn-approve" onclick="showToast('Return marked! Owner notified.')">✓ Mark Returned</button>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Resource">
                                <div class="resource-name">Circuit Theory Notes (Sem 4)</div>
                                <div class="resource-sub">Notes</div>
                            </td>
                            <td data-label="Owner">
                                <div class="person-cell">
                                    <div class="avatar" style="background:rgba(167,139,250,0.15);color:var(--purple);">H</div>
                                    <div>
                                        <div class="person-name">Hrithik Das</div>
                                        <div class="person-score">⭐ 4.9</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Borrowed On">08 Apr 2026</td>
                            <td data-label="Return By">
                                <div class="due-overdue">11 Apr 2026</div>
                                <div class="days-left">2 days overdue</div>
                            </td>
                            <td data-label="Status"><span class="badge overdue"><span class="badge-dot"></span>Overdue</span></td>
                            <td data-label="Actions">
                                <button class="action-btn btn-approve" onclick="showToast('Return marked! Owner notified.')">✓ Mark Returned</button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========== TAB: PENDING ========== --}}
    <div id="tab-pending" class="tab-panel">
        <div class="table-wrapper">
            <div class="table-header">
                <div class="table-title">Pending Borrow Requests</div>
                <p style="font-size:13px;color:var(--muted);">Approve or reject incoming requests. Borrower is notified via SMS.</p>
            </div>
            <div style="overflow-x:auto;">
                <table id="pending-table">
                    <thead>
                        <tr>
                            <th>Resource</th>
                            <th>Requester</th>
                            <th>Proposed Pickup</th>
                            <th>Return By</th>
                            <th>Message</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingRequests ?? [] as $req)
                        <tr>
                            <td data-label="Resource">
                                <div class="resource-name">{{ $req->resource->title }}</div>
                                <div class="resource-sub">{{ ucfirst($req->resource->category) }}</div>
                            </td>
                            <td data-label="Requester">
                                <div class="person-cell">
                                    <div class="avatar" style="background:rgba(96,165,250,0.15);color:var(--info);">{{ strtoupper(substr($req->requester->name,0,1)) }}</div>
                                    <div>
                                        <div class="person-name">{{ $req->requester->name }}</div>
                                        <div class="person-score">⭐ {{ $req->requester->credibility_score ?? '—' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Pickup">{{ $req->proposed_pickup->format('d M Y') }}</td>
                            <td data-label="Return By">{{ $req->proposed_return->format('d M Y') }}</td>
                            <td data-label="Message" style="max-width:160px;color:var(--muted);font-size:12px;">{{ Str::limit($req->message, 60) }}</td>
                            <td data-label="Actions">
                                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                    <form action="{{ route('requests.approve', $req->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="action-btn btn-approve">✓ Approve</button>
                                    </form>
                                    <form action="{{ route('requests.reject', $req->id) }}" method="POST" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="action-btn btn-reject">✕ Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        {{-- Demo rows --}}
                        <tr>
                            <td data-label="Resource">
                                <div class="resource-name">Arduino Starter Kit</div>
                                <div class="resource-sub">Electronics</div>
                            </td>
                            <td data-label="Requester">
                                <div class="person-cell">
                                    <div class="avatar" style="background:rgba(52,211,153,0.15);color:var(--success);">M</div>
                                    <div>
                                        <div class="person-name">Mehedi Hassan</div>
                                        <div class="person-score">⭐ 4.1</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Pickup">14 Apr 2026</td>
                            <td data-label="Return By">21 Apr 2026</td>
                            <td data-label="Message" style="max-width:160px;color:var(--muted);font-size:12px;">Need it for the robotics project submission</td>
                            <td data-label="Actions">
                                <div style="display:flex;gap:6px;">
                                    <button class="action-btn btn-approve" onclick="showToast('Approved! SMS sent to Mehedi.')">✓ Approve</button>
                                    <button class="action-btn btn-reject" onclick="showToast('Request rejected.')">✕ Reject</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Resource">
                                <div class="resource-name">Engineering Physics – Halliday</div>
                                <div class="resource-sub">Textbook</div>
                            </td>
                            <td data-label="Requester">
                                <div class="person-cell">
                                    <div class="avatar" style="background:rgba(248,113,113,0.15);color:var(--danger);">S</div>
                                    <div>
                                        <div class="person-name">Sumaiya Akter</div>
                                        <div class="person-score">⭐ 4.6</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Pickup">15 Apr 2026</td>
                            <td data-label="Return By">30 Apr 2026</td>
                            <td data-label="Message" style="max-width:160px;color:var(--muted);font-size:12px;">Mid-term prep, will return ASAP after exam</td>
                            <td data-label="Actions">
                                <div style="display:flex;gap:6px;">
                                    <button class="action-btn btn-approve" onclick="showToast('Approved! SMS sent to Sumaiya.')">✓ Approve</button>
                                    <button class="action-btn btn-reject" onclick="showToast('Request rejected.')">✕ Reject</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Resource">
                                <div class="resource-name">Multimeter – Digital</div>
                                <div class="resource-sub">Lab Equipment</div>
                            </td>
                            <td data-label="Requester">
                                <div class="person-cell">
                                    <div class="avatar" style="background:rgba(167,139,250,0.15);color:var(--purple);">K</div>
                                    <div>
                                        <div class="person-name">Karim Uddin</div>
                                        <div class="person-score">⭐ 3.7</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Pickup">13 Apr 2026</td>
                            <td data-label="Return By">16 Apr 2026</td>
                            <td data-label="Message" style="max-width:160px;color:var(--muted);font-size:12px;">Lab session tomorrow</td>
                            <td data-label="Actions">
                                <div style="display:flex;gap:6px;">
                                    <button class="action-btn btn-approve" onclick="showToast('Approved! SMS sent to Karim.')">✓ Approve</button>
                                    <button class="action-btn btn-reject" onclick="showToast('Request rejected.')">✕ Reject</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td data-label="Resource">
                                <div class="resource-name">Data Structures – Cormen</div>
                                <div class="resource-sub">Textbook</div>
                            </td>
                            <td data-label="Requester">
                                <div class="person-cell">
                                    <div class="avatar" style="background:rgba(240,192,64,0.15);color:var(--accent);">P</div>
                                    <div>
                                        <div class="person-name">Priya Sen</div>
                                        <div class="person-score">⭐ 4.9</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Pickup">18 Apr 2026</td>
                            <td data-label="Return By">02 May 2026</td>
                            <td data-label="Message" style="max-width:160px;color:var(--muted);font-size:12px;">Competitive programming practice</td>
                            <td data-label="Actions">
                                <div style="display:flex;gap:6px;">
                                    <button class="action-btn btn-approve" onclick="showToast('Approved! SMS sent to Priya.')">✓ Approve</button>
                                    <button class="action-btn btn-reject" onclick="showToast('Request rejected.')">✕ Reject</button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========== TAB: HISTORY ========== --}}
    <div id="tab-history" class="tab-panel">
        <div class="table-wrapper">
            <div class="table-header">
                <div class="table-title">Transaction History</div>
                <div class="filter-bar">
                    <input type="text" class="filter-input" placeholder="🔍 Search..." oninput="filterTable('history-table', this.value)">
                    <select class="filter-select">
                        <option value="">All Time</option>
                        <option value="month">This Month</option>
                        <option value="semester">This Semester</option>
                    </select>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table id="history-table">
                    <thead>
                        <tr>
                            <th>Resource</th>
                            <th>With</th>
                            <th>Type</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Returned On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history ?? [] as $h)
                        <tr>
                            <td data-label="Resource">
                                <div class="resource-name">{{ $h->resource->title }}</div>
                            </td>
                            <td data-label="With">{{ $h->otherParty->name }}</td>
                            <td data-label="Type">{{ $h->type }}</td>
                            <td data-label="Duration">{{ $h->issued_at->diffInDays($h->returned_at) }} days</td>
                            <td data-label="Status"><span class="badge returned"><span class="badge-dot"></span>Returned</span></td>
                            <td data-label="Returned On">{{ $h->returned_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td data-label="Resource"><div class="resource-name">Calculus – Thomas</div></td>
                            <td data-label="With">Arif Billah</td>
                            <td data-label="Type">Lending</td>
                            <td data-label="Duration">14 days</td>
                            <td data-label="Status"><span class="badge returned"><span class="badge-dot"></span>Returned</span></td>
                            <td data-label="Returned On">28 Mar 2026</td>
                        </tr>
                        <tr>
                            <td data-label="Resource"><div class="resource-name">Breadboard & Wires Kit</div></td>
                            <td data-label="With">Tasneem Jahan</td>
                            <td data-label="Type">Borrowing</td>
                            <td data-label="Duration">5 days</td>
                            <td data-label="Status"><span class="badge returned"><span class="badge-dot"></span>Returned</span></td>
                            <td data-label="Returned On">01 Mar 2026</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div id="toast" style="position:fixed;bottom:28px;right:28px;background:var(--success);color:#0d1a12;padding:14px 22px;border-radius:12px;font-family:'Syne',sans-serif;font-weight:700;font-size:14px;z-index:999;display:flex;align-items:center;gap:8px;transform:translateY(80px);opacity:0;transition:all 0.35s cubic-bezier(0.34,1.56,0.64,1);">
    <span id="toast-msg">Done!</span>
</div>

<script>
function switchTab(name, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}

function filterTable(tableId, query) {
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    const q = query.toLowerCase();
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}

function filterByStatus(tableId, status) {
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(row => {
        if (!status) { row.style.display = ''; return; }
        const badge = row.querySelector('.badge');
        if (badge && badge.classList.contains(status)) row.style.display = '';
        else row.style.display = 'none';
    });
}

function showToast(msg) {
    const toast = document.getElementById('toast');
    document.getElementById('toast-msg').textContent = msg;
    toast.style.transform = 'translateY(0)';
    toast.style.opacity = '1';
    setTimeout(() => {
        toast.style.transform = 'translateY(80px)';
        toast.style.opacity = '0';
    }, 3000);
}
</script>

</body>
</html>
