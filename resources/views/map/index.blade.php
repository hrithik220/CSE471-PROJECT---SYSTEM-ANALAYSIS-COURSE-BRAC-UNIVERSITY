@extends('layouts.app')
@section('title','Campus Map')
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
#campus-map { height: calc(100vh - 220px); min-height: 500px; border-radius: 1rem; }
.leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
.resource-popup img { width: 100%; height: 80px; object-fit: cover; border-radius: 8px; margin-bottom: 8px; }
.resource-popup h4 { font-weight: 700; font-size: 14px; margin-bottom: 4px; }
.resource-popup p { font-size: 12px; color: #64748b; margin: 2px 0; }
.resource-popup a { display:inline-block; margin-top:8px; background:#2563eb; color:#fff; padding:5px 12px; border-radius:8px; font-size:12px; font-weight:600; text-decoration:none; }
.badge-free { background:#dcfce7; color:#166534; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600; display:inline-block; margin-bottom:4px; }
.badge-exchange { background:#ffedd5; color:#9a3412; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600; display:inline-block; margin-bottom:4px; }
</style>
@endpush
@section('content')
<div class="mb-5 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Campus Resource Map</h1>
        <p class="text-slate-500 text-sm mt-1">Find available resources near you — click markers for details</p>
    </div>
    <div class="flex gap-3 text-sm">
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span> Free Lending</span>
        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-orange-400 inline-block"></span> Exchange</span>
    </div>
</div>

{{-- Filter bar --}}
<div class="bg-white rounded-2xl border border-slate-200 p-4 mb-4 flex gap-3 flex-wrap items-center">
    <label class="text-sm font-medium text-slate-600">Filter:</label>
    <button onclick="filterMarkers('all')" id="btn-all" class="filter-btn active px-4 py-1.5 rounded-xl text-sm font-semibold border border-blue-600 bg-blue-600 text-white transition-all">All</button>
    <button onclick="filterMarkers('free_lending')" id="btn-free_lending" class="filter-btn px-4 py-1.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:border-blue-400 transition-all">Free Lending</button>
    <button onclick="filterMarkers('exchange')" id="btn-exchange" class="filter-btn px-4 py-1.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:border-orange-400 transition-all">Exchange</button>
    <span id="map-count" class="ml-auto text-sm text-slate-400"></span>
</div>

<div id="campus-map" class="border border-slate-200 shadow-sm"></div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const map = L.map('campus-map').setView([23.8103, 90.4125], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors',
    maxZoom: 19
}).addTo(map);

const icons = {
    free_lending: L.divIcon({
        className: '',
        html: `<div style="background:#2563eb;width:34px;height:34px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 3px 10px rgba(0,0,0,0.3);"></div>`,
        iconSize: [34, 34], iconAnchor: [17, 34], popupAnchor: [0, -36]
    }),
    exchange: L.divIcon({
        className: '',
        html: `<div style="background:#f97316;width:34px;height:34px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 3px 10px rgba(0,0,0,0.3);"></div>`,
        iconSize: [34, 34], iconAnchor: [17, 34], popupAnchor: [0, -36]
    })
};

let allMarkers = [];
let currentFilter = 'all';

function filterMarkers(type) {
    currentFilter = type;
    document.querySelectorAll('.filter-btn').forEach(b => {
        b.className = 'filter-btn px-4 py-1.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-600 hover:border-blue-400 transition-all';
    });
    const activeBtn = document.getElementById('btn-' + type);
    if (activeBtn) activeBtn.className = 'filter-btn active px-4 py-1.5 rounded-xl text-sm font-semibold border border-blue-600 bg-blue-600 text-white transition-all';

    let visible = 0;
    allMarkers.forEach(({marker, resource}) => {
        if (type === 'all' || resource.type === type) {
            marker.addTo(map);
            visible++;
        } else {
            map.removeLayer(marker);
        }
    });
    document.getElementById('map-count').textContent = visible + ' resource' + (visible !== 1 ? 's' : '') + ' shown';
}

fetch('{{ route("map.resources") }}')
    .then(r => r.json())
    .then(resources => {
        if (!resources.length) {
            document.getElementById('map-count').textContent = 'No resources with location data';
            return;
        }

        const bounds = [];
        resources.forEach(r => {
            const marker = L.marker([r.lat, r.lng], { icon: icons[r.type] || icons.free_lending });

            const typeClass = r.type === 'free_lending' ? 'badge-free' : 'badge-exchange';
            const typeLabel = r.type === 'free_lending' ? '🎁 Free Lending' : '🔄 Exchange';
            const stars = '★'.repeat(Math.round(r.credibility)) + '☆'.repeat(5 - Math.round(r.credibility));

            marker.bindPopup(`
                <div class="resource-popup" style="min-width:180px">
                    ${r.photo_url ? `<img src="${r.photo_url}" alt="${r.title}">` : ''}
                    <span class="${typeClass}">${typeLabel}</span>
                    <h4>${r.title}</h4>
                    <p>📦 ${r.category} · ${r.condition}</p>
                    <p>👤 ${r.owner} <span style="color:#f59e0b">${stars}</span> ${r.credibility}</p>
                    ${r.location ? `<p>📍 ${r.location}</p>` : ''}
                    <a href="${r.url}">View & Request →</a>
                </div>
            `, { maxWidth: 240 });

            allMarkers.push({ marker, resource: r });
            bounds.push([r.lat, r.lng]);
        });

        filterMarkers('all');

        if (bounds.length > 1) {
            map.fitBounds(bounds, { padding: [40, 40] });
        } else if (bounds.length === 1) {
            map.setView(bounds[0], 16);
        }
    })
    .catch(() => {
        document.getElementById('map-count').textContent = 'Could not load resource locations';
    });
</script>
@endpush
