@props(['class' => ''])

<p {{ $attributes->merge(['class' => 'text-xs sm:text-sm text-muted-foreground ' . $class]) }}>
    {{ $slot }}
</p>

