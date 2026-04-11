@extends('layouts.app')
@section('title', 'Pickup Locator')

@section('content')
<div style="height: calc(100vh - 64px); display: flex; flex-direction: column;">

    {{-- TOP BAR --}}
    <div class="bg-white border-b px-4 py-3 flex items-center gap-4 flex-shrink-0 shadow-sm">
        <div>
            <h1 class="text-lg font-bold text-gray-800">📍 Pickup Locator</h1>
            <p class="text-xs text-gray-500">Find resources near your campus</p>
        </div>

        {{-- Category Filter --}}
        <form method="GET" action="{{ route('map.index') }}" class="flex items-center gap-2 ml-4">
            <select name="category" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-xl px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category')==$cat->id ? 'selected':'' }}>
                        {{ $cat->icon }} {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <div class="ml-auto text-sm text-gray-500">
            <span class="font-semibold text-gray-800">{{ $resources->count() }}</span> resources found
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div style="flex: 1; display: flex; overflow: hidden;">

        {{-- SIDEBAR --}}
        <div style="width: 340px; background: white; border-right: 1px solid #E5E7EB; overflow-y: auto; flex-shrink: 0;">
            @forelse($resources as $resource)
            <div class="resource-card p-4 border-b border-gray-50 cursor-pointer hover:bg-blue-50 transition"
                 data-id="{{ $resource->id }}"
                 data-lat="{{ $resource->pickup_lat }}"
                 data-lng="{{ $resource->pickup_lng }}"
                 onclick="focusPin({{ $resource->id }}, {{ $resource->pickup_lat }}, {{ $resource->pickup_lng }})">
                <div class="flex gap-3">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0"
                         style="background: #DBEAFE">{{ $resource->category->icon }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-gray-900 truncate">{{ $resource->title }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">by {{ $resource->owner->name }} · ⭐ {{ number_format($resource->owner->credibility_score,1) }}</p>
                        <div class="flex gap-1 mt-1.5 flex-wrap">
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                {{ $resource->condition==='new' ? 'bg-green-100 text-green-700' :
                                   ($resource->condition==='good' ? 'bg-blue-100 text-blue-700' :
                                   ($resource->condition==='fair' ? 'bg-yellow-100 text-yellow-700':'bg-red-100 text-red-600')) }}">
                                {{ ucfirst($resource->condition) }}
                            </span>
                            <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full font-medium">
                                {{ $resource->sharing_type==='free' ? '🆓 Free':'🔄 Exchange' }}
                            </span>
                        </div>
                        <p class="text-xs text-blue-600 mt-1.5 font-medium">📍 {{ $resource->pickup_address }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-gray-400">
                <p class="text-4xl mb-2">📭</p>
                <p class="text-sm">No resources with pickup locations</p>
            </div>
            @endforelse
        </div>

        {{-- MAP --}}
        <div style="flex: 1; position: relative;">
            <div id="map" style="width: 100%; height: 100%;"></div>

            {{-- Info Card --}}
            <div id="info-card" class="hidden"
                 style="position: absolute; bottom: 24px; left: 50%; transform: translateX(-50%);
                        background: white; border-radius: 20px; padding: 18px 22px;
                        box-shadow: 0 8px 40px rgba(0,0,0,0.15); min-width: 320px;
                        border: 1px solid #E5E7EB; z-index: 10;">
                <div class="flex items-start gap-3">
                    <div id="card-icon" class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0" style="background:#DBEAFE"></div>
                    <div class="flex-1">
                        <p id="card-title" class="font-bold text-gray-900 text-base"></p>
                        <p id="card-owner" class="text-xs text-gray-500 mt-0.5"></p>
                        <div id="card-badges" class="flex gap-1 mt-1.5 flex-wrap"></div>
                    </div>
                </div>
                <div class="border-t border-gray-100 mt-3 pt-3">
                    <p id="card-address" class="text-sm text-gray-700 flex items-center gap-1">
                        <span>📍</span><span></span>
                    </p>
                </div>
                <div class="flex gap-2 mt-3">
                    <a id="card-view-btn" href="#"
                       class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                        View Resource
                    </a>
                    <button onclick="document.getElementById('info-card').classList.add('hidden')"
                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm font-medium">
                        ✕
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Resource data from Laravel
    const resources = @json($resources->map(function($r) {
        return [
            'id'          => $r->id,
            'title'       => $r->title,
            'icon'        => $r->category->icon,
            'category'    => $r->category->name,
            'condition'   => $r->condition,
            'sharing'     => $r->sharing_type,
            'owner'       => $r->owner->name,
            'credibility' => $r->owner->credibility_score,
            'address'     => $r->pickup_address,
            'lat'         => (float)$r->pickup_lat,
            'lng'         => (float)$r->pickup_lng,
            'url'         => route('resources.show', $r->id),
        ];
    }));

    let map, markers = {};

    function initMap() {
        // Center on BRACU campus
        const center = { lat: 23.7809, lng: 90.4012 };

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 17,
            center: center,
            mapTypeControl: false,
            streetViewControl: false,
            styles: [
                { featureType: 'poi', stylers: [{ visibility: 'off' }] }
            ]
        });

        // Add pins for each resource
        resources.forEach(r => {
            const marker = new google.maps.Marker({
                position: { lat: r.lat, lng: r.lng },
                map: map,
                title: r.title,
                label: {
                    text: r.icon,
                    fontSize: '20px',
                },
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 22,
                    fillColor: '#2563EB',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 3,
                },
            });

            marker.addListener('click', () => showInfoCard(r));
            markers[r.id] = marker;
        });
    }

    function showInfoCard(r) {
        document.getElementById('card-icon').textContent = r.icon;
        document.getElementById('card-title').textContent = r.title;
        document.getElementById('card-owner').textContent = 'by ' + r.owner + ' · ⭐ ' + r.credibility;
        document.getElementById('card-address').querySelector('span:last-child').textContent = r.address;
        document.getElementById('card-view-btn').href = r.url;

        const badges = document.getElementById('card-badges');
        badges.innerHTML = `
            <span style="font-size:10px;font-weight:600;padding:3px 10px;border-radius:99px;background:#DBEAFE;color:#1D4ED8">${r.condition}</span>
            <span style="font-size:10px;font-weight:600;padding:3px 10px;border-radius:99px;background:#EDE9FE;color:#5B21B6">${r.sharing === 'free' ? '🆓 Free' : '🔄 Exchange'}</span>
        `;

        document.getElementById('info-card').classList.remove('hidden');

        // Highlight sidebar card
        document.querySelectorAll('.resource-card').forEach(c => c.style.background = '');
        const card = document.querySelector(`.resource-card[data-id="${r.id}"]`);
        if (card) card.style.background = '#EFF6FF';
    }

    function focusPin(id, lat, lng) {
        map.panTo({ lat: parseFloat(lat), lng: parseFloat(lng) });
        map.setZoom(18);
        const r = resources.find(r => r.id === id);
        if (r) showInfoCard(r);
    }
</script>

{{-- Load Google Maps --}}
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&callback=initMap">
</script>
@endsection
