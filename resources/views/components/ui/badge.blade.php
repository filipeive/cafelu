@props([
    'variant' => 'default',
    'class' => ''
])

@php
    $baseClasses = "inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2";

    $variants = [
        'default' => 'border-transparent bg-primary text-primary-foreground shadow-2xs hover:bg-primary/80',
        'secondary' => 'border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80',
        'destructive' => 'border-transparent bg-destructive/15 text-destructive border-destructive/20 hover:bg-destructive/20',
        'outline' => 'text-foreground border-border',
        'success' => 'border-emerald-200/60 bg-emerald-50 text-emerald-700 dark:border-emerald-800/40 dark:bg-emerald-950/50 dark:text-emerald-400',
        'warning' => 'border-amber-200/60 bg-amber-50 text-amber-700 dark:border-amber-800/40 dark:bg-amber-950/50 dark:text-amber-400',
        'info' => 'border-blue-200/60 bg-blue-50 text-blue-700 dark:border-blue-800/40 dark:bg-blue-950/50 dark:text-blue-400',
    ];

    $variantClass = $variants[$variant] ?? $variants['default'];
    $classes = "{$baseClasses} {$variantClass} {$class}";
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>

