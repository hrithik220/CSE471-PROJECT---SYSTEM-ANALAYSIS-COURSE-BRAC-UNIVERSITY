{{-- Samantha | Module 1: Post Resource --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share a Resource – CampusShare</title>
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
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
        }
        h1, h2, h3, .font-display { font-family: 'Syne', sans-serif; }

        /* Noise overlay */
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
            max-width: 860px;
            margin: 0 auto;
            padding: 48px 24px 80px;
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

        .page-title {
            font-size: clamp(2rem, 5vw, 3.2rem);
            font-weight: 800;
            line-height: 1.1;
            color: var(--text);
            margin-bottom: 8px;
        }
        .page-title span { color: var(--accent); }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px;
            margin-top: 36px;
            transition: border-color 0.2s;
        }
        .card:hover { border-color: rgba(240,192,64,0.25); }

        .card-heading {
            font-family: 'Syne', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-heading::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .form-group { margin-bottom: 22px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 7px;
            letter-spacing: 0.01em;
        }
        .form-label .req { color: var(--accent2); margin-left: 2px; }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            padding: 12px 16px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            appearance: none;
        }
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(240,192,64,0.1);
        }
        .form-textarea { resize: vertical; min-height: 110px; }
        .form-select option { background: var(--surface2); }

        .input-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        @media (max-width: 600px) { .input-grid { grid-template-columns: 1fr; } }

        /* Toggle pills */
        .toggle-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .toggle-pill {
            position: relative;
            cursor: pointer;
        }
        .toggle-pill input { position: absolute; opacity: 0; width: 0; height: 0; }
        .toggle-pill span {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 99px;
            border: 1px solid var(--border);
            background: var(--surface2);
            color: var(--muted);
            font-size: 13px;
            font-weight: 500;
            transition: all 0.18s;
            cursor: pointer;
        }
        .toggle-pill input:checked + span {
            background: rgba(240,192,64,0.12);
            border-color: var(--accent);
            color: var(--accent);
        }

        /* Condition badges */
        .condition-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .condition-pill {
            position: relative;
            cursor: pointer;
        }
        .condition-pill input { position: absolute; opacity: 0; width: 0; height: 0; }
        .condition-pill span {
            display: block;
            padding: 8px 20px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--surface2);
            color: var(--muted);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.18s;
        }
        .condition-pill input:checked + span {
            border-color: var(--success);
            background: rgba(52,211,153,0.1);
            color: var(--success);
        }

        /* Upload zone */
        .upload-zone {
            border: 2px dashed var(--border);
            border-radius: 12px;
            padding: 40px 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--surface2);
            position: relative;
        }
        .upload-zone:hover,
        .upload-zone.drag-over {
            border-color: var(--accent);
            background: rgba(240,192,64,0.05);
        }
        .upload-zone input {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        .upload-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(240,192,64,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            color: var(--accent);
            font-size: 22px;
        }
        .upload-hint { font-size: 13px; color: var(--muted); margin-top: 6px; }
        .upload-hint span { color: var(--accent); font-weight: 600; }

        #preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 10px;
            margin-top: 16px;
        }
        .preview-thumb {
            aspect-ratio: 1;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border);
        }

        /* Availability duration */
        .duration-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .duration-row .form-input { flex: 1; }
        .duration-unit {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 16px;
            color: var(--muted);
            font-size: 13px;
            min-width: 100px;
        }

        /* Date range */
        .date-range {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .date-range .form-input { flex: 1; }
        .date-sep { color: var(--muted); font-size: 13px; white-space: nowrap; }

        /* Submit btn */
        .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 16px;
            background: var(--accent);
            color: #0d0f14;
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.04em;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 32px;
        }
        .btn-submit:hover {
            background: #f7d060;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(240,192,64,0.25);
        }
        .btn-submit:active { transform: translateY(0); }

        /* Success toast */
        #toast {
            position: fixed;
            bottom: 28px;
            right: 28px;
            background: var(--success);
            color: #0d1a12;
            padding: 14px 22px;
            border-radius: 12px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 14px;
            z-index: 999;
            display: flex;
            align-items: center;
            gap: 8px;
            transform: translateY(80px);
            opacity: 0;
            transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        #toast.show { transform: translateY(0); opacity: 1; }

        .helper-text { font-size: 12px; color: var(--muted); margin-top: 5px; }

        .char-counter {
            font-size: 11px;
            color: var(--muted);
            text-align: right;
            margin-top: 4px;
        }
        .char-counter.warn { color: var(--accent2); }
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
            <a href="{{ route('dashboard') }}" style="color:var(--muted);font-size:13px;text-decoration:none;font-weight:500;">Dashboard</a>
            {{-- BUG FIX: route('resources.index') did not exist, causing RouteNotFoundException.
                 Changed to dashboard route which is the closest defined route. --}}
            <a href="{{ route('dashboard') }}" style="color:var(--muted);font-size:13px;text-decoration:none;font-weight:500;">Browse</a>
            <div style="width:32px;height:32px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;color:#0d0f14;font-family:'Syne',sans-serif;font-weight:800;font-size:13px;">
                {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
            </div>
        </div>
    </div>
</nav>

<div class="page-wrapper">
    {{-- Header --}}
    <div>
        <div class="section-tag">
            <svg width="8" height="8" viewBox="0 0 8 8" fill="currentColor"><circle cx="4" cy="4" r="4"/></svg>
            Module 1 · Samantha
        </div>
        <h1 class="page-title">Share a <span>Resource</span></h1>
        <p style="color:var(--muted);font-size:15px;margin-top:8px;max-width:520px;">
            List your textbook, notes, lab equipment, or electronics for other students to borrow or exchange.
        </p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div style="background:rgba(52,211,153,0.12);border:1px solid var(--success);border-radius:10px;padding:12px 16px;margin-top:24px;color:var(--success);font-size:14px;display:flex;align-items:center;gap:8px;">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div style="background:rgba(248,113,113,0.1);border:1px solid var(--danger);border-radius:10px;padding:12px 16px;margin-top:24px;color:var(--danger);font-size:14px;">
            @foreach($errors->all() as $error)
                <div>· {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('resources.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
        @csrf

        {{-- SECTION: Basic Info --}}
        <div class="card">
            <div class="card-heading">① Basic Information</div>

            <div class="form-group">
                <label class="form-label">Resource Title <span class="req">*</span></label>
                <input type="text" name="title" class="form-input" placeholder="e.g. Engineering Physics – Halliday 10th Ed" maxlength="120" id="titleInput" value="{{ old('title') }}" required>
                <div class="char-counter" id="titleCounter">0 / 120</div>
            </div>

            <div class="input-grid">
                <div class="form-group">
                    <label class="form-label">Category <span class="req">*</span></label>
                    <select name="category" class="form-select" required>
                        <option value="" disabled selected>Select category</option>
                        <option value="textbook" {{ old('category')=='textbook'?'selected':'' }}>📚 Textbook</option>
                        <option value="notes" {{ old('category')=='notes'?'selected':'' }}>📝 Notes / Handouts</option>
                        <option value="lab_equipment" {{ old('category')=='lab_equipment'?'selected':'' }}>🔬 Lab Equipment</option>
                        <option value="electronics" {{ old('category')=='electronics'?'selected':'' }}>💻 Electronics</option>
                        <option value="stationery" {{ old('category')=='stationery'?'selected':'' }}>✏️ Stationery</option>
                        <option value="other" {{ old('category')=='other'?'selected':'' }}>📦 Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Condition <span class="req">*</span></label>
                    <select name="condition" class="form-select" required>
                        <option value="" disabled selected>Select condition</option>
                        <option value="new" {{ old('condition')=='new'?'selected':'' }}>🌟 New / Like New</option>
                        <option value="good" {{ old('condition')=='good'?'selected':'' }}>✅ Good</option>
                        <option value="fair" {{ old('condition')=='fair'?'selected':'' }}>🔶 Fair</option>
                        <option value="poor" {{ old('condition')=='poor'?'selected':'' }}>⚠️ Poor but Usable</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description <span class="req">*</span></label>
                <textarea name="description" class="form-textarea" placeholder="Describe the resource – edition, missing pages, any damage, accessories included, etc." maxlength="800" id="descInput" required>{{ old('description') }}</textarea>
                <div class="char-counter" id="descCounter">0 / 800</div>
            </div>
        </div>

        {{-- SECTION: Sharing Type --}}
        <div class="card">
            <div class="card-heading">② Sharing Type</div>

            <div class="form-group">
                <label class="form-label">How do you want to share this? <span class="req">*</span></label>
                <div class="toggle-group">
                    <label class="toggle-pill">
                        <input type="radio" name="sharing_type" value="free_lending" {{ old('sharing_type','free_lending')=='free_lending'?'checked':'' }}>
                        <span>🤝 Free Lending</span>
                    </label>
                    <label class="toggle-pill">
                        <input type="radio" name="sharing_type" value="exchange" {{ old('sharing_type')=='exchange'?'checked':'' }}>
                        <span>🔄 Exchange Based</span>
                    </label>
                    <label class="toggle-pill">
                        <input type="radio" name="sharing_type" value="both" {{ old('sharing_type')=='both'?'checked':'' }}>
                        <span>✨ Either Works</span>
                    </label>
                </div>
            </div>

            <div id="exchangeNote" style="display:none;margin-top:8px;">
                <div class="form-group">
                    <label class="form-label">What would you like in exchange?</label>
                    <input type="text" name="exchange_note" class="form-input" placeholder="e.g. Calculus textbook, USB drive, printed notes..." value="{{ old('exchange_note') }}">
                </div>
            </div>
        </div>

        {{-- SECTION: Availability --}}
        <div class="card">
            <div class="card-heading">③ Availability Window</div>

            <div class="form-group">
                <label class="form-label">Available From – Until <span class="req">*</span></label>
                <div class="date-range">
                    <input type="date" name="available_from" class="form-input" value="{{ old('available_from', now()->format('Y-m-d')) }}" required>
                    <span class="date-sep">→</span>
                    <input type="date" name="available_until" class="form-input" value="{{ old('available_until', now()->addMonths(2)->format('Y-m-d')) }}" required>
                </div>
                <p class="helper-text">Set the window during which you're willing to lend this resource.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Maximum Borrow Duration</label>
                <div class="duration-row">
                    <input type="number" name="max_borrow_days" class="form-input" placeholder="7" min="1" max="180" value="{{ old('max_borrow_days', 7) }}">
                    <div class="duration-unit">days per borrower</div>
                </div>
                <p class="helper-text">How many days can one person keep this resource at a time?</p>
            </div>

            <div class="form-group">
                <label class="form-label">Pickup Location / Campus Area</label>
                <input type="text" name="pickup_location" class="form-input" placeholder="e.g. CSE Building, Room 301 or Library Gate B" value="{{ old('pickup_location') }}">
            </div>
        </div>

        {{-- SECTION: Photos --}}
        <div class="card">
            <div class="card-heading">④ Photos</div>

            <div class="upload-zone" id="uploadZone">
                <input type="file" name="photos[]" id="photoInput" accept="image/*" multiple>
                <div class="upload-icon">📷</div>
                <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:15px;">Drop photos here</div>
                <p class="upload-hint">or <span>click to browse</span> — JPEG, PNG, WebP up to 5MB each</p>
                <p class="helper-text" style="margin-top:10px;">Up to 6 photos. Clear photos attract more borrowers.</p>
            </div>

            <div id="preview-grid"></div>
        </div>

        {{-- SECTION: Extra Tags --}}
        <div class="card">
            <div class="card-heading">⑤ Additional Info</div>
            <div class="input-grid">
                <div class="form-group">
                    <label class="form-label">Course Code (optional)</label>
                    <input type="text" name="course_code" class="form-input" placeholder="e.g. CSE-101, PHY-201" value="{{ old('course_code') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Department (optional)</label>
                    <input type="text" name="department" class="form-input" placeholder="e.g. Computer Science" value="{{ old('department') }}">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Tags (comma-separated)</label>
                <input type="text" name="tags" class="form-input" placeholder="e.g. physics, semester-2, calculus, lab" value="{{ old('tags') }}">
                <p class="helper-text">Tags help other students discover your resource more easily.</p>
            </div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-submit" id="submitBtn">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
            Post Resource to CampusShare
        </button>
    </form>
</div>

{{-- Toast --}}
<div id="toast">✓ Resource posted successfully!</div>

<script>
// Char counters
function setupCounter(inputId, counterId, max) {
    const input = document.getElementById(inputId);
    const counter = document.getElementById(counterId);
    if (!input || !counter) return;
    input.addEventListener('input', () => {
        const len = input.value.length;
        counter.textContent = `${len} / ${max}`;
        counter.classList.toggle('warn', len > max * 0.85);
    });
}
setupCounter('titleInput', 'titleCounter', 120);
setupCounter('descInput', 'descCounter', 800);

// Exchange note toggle
document.querySelectorAll('input[name="sharing_type"]').forEach(r => {
    r.addEventListener('change', () => {
        const show = r.value === 'exchange' || r.value === 'both';
        document.getElementById('exchangeNote').style.display = show ? 'block' : 'none';
    });
});

// Photo preview
const photoInput = document.getElementById('photoInput');
const previewGrid = document.getElementById('preview-grid');
const uploadZone = document.getElementById('uploadZone');

photoInput.addEventListener('change', () => renderPreviews(photoInput.files));

uploadZone.addEventListener('dragover', e => { e.preventDefault(); uploadZone.classList.add('drag-over'); });
uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('drag-over'));
uploadZone.addEventListener('drop', e => {
    e.preventDefault();
    uploadZone.classList.remove('drag-over');
    renderPreviews(e.dataTransfer.files);
});

function renderPreviews(files) {
    previewGrid.innerHTML = '';
    const max = 6;
    Array.from(files).slice(0, max).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'preview-thumb';
            previewGrid.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}

// Form submit animation
document.getElementById('postForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.textContent = 'Posting...';
});

// Show toast if session flash (simulate for demo)
@if(session('success'))
    const toast = document.getElementById('toast');
    setTimeout(() => toast.classList.add('show'), 200);
    setTimeout(() => toast.classList.remove('show'), 3500);
@endif
</script>

</body>
</html>
