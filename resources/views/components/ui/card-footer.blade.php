@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'flex items-center p-5 sm:p-6 pt-0 sm:pt-0 ' . $class]) }}>
    {{ $slot }}
</div>

