@props(['status'])
@php
    $colors = [
        'active' => 'bg-forest/10 text-forest',
        'approved' => 'bg-forest/10 text-forest',
        'completed' => 'bg-forest/10 text-forest',
        'resolved' => 'bg-forest/10 text-forest',
        'pending' => 'bg-mandarin/10 text-mandarin-dark',
        'investigating' => 'bg-mandarin/10 text-mandarin-dark',
        'searching' => 'bg-crest text-forest',
        'requested' => 'bg-crest text-forest',
        'accepted' => 'bg-mandarin/10 text-mandarin-dark',
        'arrived' => 'bg-ink/10 text-ink',
        'ongoing' => 'bg-ink/10 text-ink',
        'open' => 'bg-mandarin/10 text-mandarin-dark',
        'rejected' => 'bg-red-100 text-red-800',
        'cancelled' => 'bg-ink/10 text-ink/60',
        'suspended' => 'bg-red-100 text-red-800',
    ];
    $color = $colors[$status] ?? 'bg-ink/5 text-ink/70';
@endphp
<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 wk-label '.$color]) }}>
    {{ ucfirst($status) }}
</span>
