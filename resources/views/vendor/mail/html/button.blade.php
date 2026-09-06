@props([
    'url',
    'color' => 'purple',
    'align' => 'center',
])

<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
<a href="{{ $url }}" class="button button-purple" target="_blank" rel="noopener" style="background-color: #61398F; border-top: 10px solid #61398F; border-right: 18px solid #61398F; border-bottom: 10px solid #61398F; border-left: 18px solid #61398F; display: inline-block; color: #ffffff; text-decoration: none; border-radius: 8px; box-shadow: 0 4px 10px rgba(97, 57, 143, 0.2); -webkit-text-size-adjust: none; box-sizing: border-box; font-weight: bold; font-family: Arial, sans-serif;">{!! $slot !!}</a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>