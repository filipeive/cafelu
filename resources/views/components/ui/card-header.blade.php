@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-1.5 p-5 sm:p-6 ' . $class]) }}>
    {{ $slot }}
</div>

