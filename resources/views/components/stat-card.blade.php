@props([
    'title',
    'value' => null,
    'helper' => null,
    'tone' => 'neutral',
])

<div {{ $attributes->class(['stat-card', 'stat-card--'.$tone]) }}>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="small text-uppercase text-muted fw-semibold">{{ $title }}</div>
        @if (isset($action))
            {{ $action }}
        @endif
    </div>
    @if ($value !== null)
        <div class="stat-card__value">{{ $value }}</div>
    @endif
    @if (isset($content))
        {{ $content }}
    @endif
    @if ($helper)
        <div class="small text-muted mt-2">{{ $helper }}</div>
    @endif
</div>
