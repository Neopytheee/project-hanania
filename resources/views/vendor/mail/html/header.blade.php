@php
    $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania Travel');
    $logoPath = \App\Models\AppInformation::getValue('company_logo');
    $logoUrl = $logoPath ? asset('storage/' . $logoPath) : asset('images/HananiaNew4K.png');
@endphp

<tr>
    <td class="header" style="text-align: center; padding: 25px 0;">
        <a href="{{ url('/') }}" style="display: inline-block; text-decoration: none;">
            {{-- Menampilkan Logo Dinamis dari Database --}}
            <!-- <img src="{{ $logoUrl }}" alt="{{ $companyName }}" style="max-height: 45px; width: auto; object-fit: contain;"> -->
             <img src="https://via.placeholder.com/150x50.png?text=Hanania+Travel" alt="Hanania Travel" style="max-height: 45px; width: auto; object-fit: contain;">
        </a>
    </td>
</tr>