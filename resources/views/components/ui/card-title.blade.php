@props(['class' => ''])

<h3 {{ $attributes->merge(['class' => 'font-semibold leading-none tracking-tight text-card-foreground ' . $class]) }}>
    {{ $slot }}
</h3>
