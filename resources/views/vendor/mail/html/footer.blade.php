@php
    $companyName = \App\Models\AppInformation::getValue('company_name', 'Hanania Travel');
@endphp

<tr>
    <td class="footer" style="text-align: center; padding: 25px; color: #888888; font-size: 12px; font-family: Arial, sans-serif;">
        <p style="margin: 0 0 8px 0;">
            &copy; {{ date('Y') }} {{ $companyName }}. Seluruh hak cipta dilindungi.
        </p>
        <p style="margin: 0; color: #aaaaaa; font-size: 11px;">
            Pesan ini dikirim secara otomatis oleh sistem, mohon tidak membalas email ini.
        </p>
    </td>
</tr>