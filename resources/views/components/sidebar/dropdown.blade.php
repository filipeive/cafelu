@props(['icon', 'title', 'id', 'badge' => null, 'badgeClass' => 'badge-primary'])

@php
    // Verificar se alguma rota filha está ativa
    $isActive = false;

    // Vamos usar o conteúdo do slot para verificar se há itens ativos
    $content = $slot->toHtml();
    if (strpos($content, 'active') !== false) {
        $isActive = true;
    }
@endphp

<li class="mb-1" x-data="{ open: {{ $isActive ? 'true' : 'false' }} }">
    <a class="flex items-center px-3 py-2 rounded-lg transition-colors w-full text-left cursor-pointer group {{ $isActive ? 'text-primary bg-orange-50 dark:bg-gray-800' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}"
        :class="{ 'justify-center': sidebarCollapsed }"
        @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; localStorage.setItem('sidebarCollapsed', false); } else { open = !open; }"
        :aria-expanded="open">
        @if (!empty($icon))
            <i class="mdi {{ $icon }} text-xl {{ $isActive ? 'text-primary' : 'text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300' }}"
                :class="{ 'mr-3': !sidebarCollapsed }"></i>
        @endif
        <span class="font-medium flex-1" x-show="!sidebarCollapsed" x-transition>{{ $title }}</span>

        @if (isset($badge) && $badge)
            <span class="{{ $badgeClass }} px-2 py-0.5 rounded text-xs font-semibold ml-2" x-show="!sidebarCollapsed"
                x-transition>
                {{ $badge }}
            </span>
        @endif

        <i class="mdi mdi-chevron-down ml-auto transition-transform duration-200" :class="{ 'rotate-180': open }"
            x-show="!sidebarCollapsed" x-transition></i>
    </a>
    <div x-show="open && !sidebarCollapsed" x-collapse class="{{ $isActive ? '' : 'hidden' }}"
        :class="{ 'hidden': !open || sidebarCollapsed }">
        <ul class="mt-1 space-y-1">
            {{ $slot }}
        </ul>
    </div>
</li>