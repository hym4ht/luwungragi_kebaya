@props([
    'title',
    'subtitle' => null,
])

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3 mb-4">
    <div>
        <p class="eyebrow mb-2">Luwungragi Dashboard</p>
        <h1 class="display-title mb-2">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-muted mb-0">{{ $subtitle }}</p>
        @endif
    </div>

    @if (trim((string) $slot) !== '')
        <div>
            {{ $slot }}
        </div>
    @endif
</div>
