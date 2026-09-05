@php
    // Cari jadwal keberangkatan jamaah ini (lewat relasi group)
    $activeMembership = $enrollment->groupMemberships()->where('status', 'active')->first();
    $departure = $activeMembership ? $activeMembership->group->departure : null;
@endphp

<!-- Munculkan kartu ini JIKA jamaah sudah punya jadwal keberangkatan -->
@if($departure)
<div class="bg-gradient-to-br from-purple-50 to-white p-6 rounded-xl shadow-sm border border-purple-100 mb-6">
    <div class="flex items-center gap-3 border-b border-purple-100 pb-3 mb-4">
        <span class="bg-hanania-purple text-white p-2 rounded-lg text-lg">🕋</span>
        <div>
            <h4 class="font-black text-gray-800">Persiapan Keberangkatan</h4>
            <p class="text-[11px] text-gray-500">Kloter: <strong>{{ $departure->name }}</strong> ({{ \Carbon\Carbon::parse($departure->departure_date)->format('d M Y') }})</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Info Manasik -->
        <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm">
            <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Jadwal Manasik</h5>
            @if($departure->manasik_date)
                <p class="font-bold text-gray-800 text-sm mb-1">📅 {{ \Carbon\Carbon::parse($departure->manasik_date)->format('l, d F Y - H:i') }} WIB</p>
                <p class="text-xs text-gray-600">📍 {{ $departure->manasik_location ?? 'Lokasi menyusul' }}</p>
            @else
                <p class="text-xs text-gray-400 italic">Jadwal manasik belum ditentukan oleh Admin.</p>
            @endif
        </div>

        <!-- Download Itinerary -->
        <div class="bg-white p-4 rounded-lg border border-gray-100 shadow-sm flex flex-col justify-center items-start">
            <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dokumen Perjalanan</h5>
            @if($departure->itinerary_file)
                <a href="{{ asset('storage/' . $departure->itinerary_file) }}" target="_blank" class="w-full text-center bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition font-bold text-xs px-4 py-2.5 rounded-lg shadow-sm">
                    📄 Unduh Itinerary & E-Ticket
                </a>
            @else
                <p class="text-xs text-gray-400 italic">Dokumen itinerary sedang dipersiapkan.</p>
            @endif
        </div>
    </div>
</div>
@endif