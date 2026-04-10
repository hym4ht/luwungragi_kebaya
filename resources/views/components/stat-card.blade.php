@props([
    'title',
    'value',
    'helper' => null,
    'tone' => 'neutral',
])

<div {{ $attributes->class(['stat-card', 'stat-card--'.$tone]) }}>
    <div class="small text-uppercase text-muted fw-semibold mb-2">{{ $title }}</div>
    <div class="stat-card__value">{{ $value }}</div>
    @if ($helper)
        <div class="small text-muted mt-2">{{ $helper }}</div>
    @endif
</div>
