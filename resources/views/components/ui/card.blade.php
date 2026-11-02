@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-border/70 bg-card text-card-foreground shadow-xs ' . $class]) }}>
    {{ $slot }}
</div>

