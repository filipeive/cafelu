@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'p-5 sm:p-6 pt-0 sm:pt-0 ' . $class]) }}>
    {{ $slot }}
</div>

