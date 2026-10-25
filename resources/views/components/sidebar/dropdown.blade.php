@props(['icon', 'title', 'id', 'badge' => null, 'badgeClass' => 'bg-gray-100 text-gray-700'])

@php
    $content = $slot->toHtml();
    $isActive = strpos($content, 'bg-orange-500/10') !== false || strpos($content, 'font-semibold') !== false || strpos($content, 'text-orange-600') !== false;
@endphp

<li class="relative mb-1 group" x-data="{ open: {{ $isActive ? 'true' : 'false' }} }">
    <button type="button"
            class="relative flex items-center w-full px-3 py-2 rounded-xl text-sm font-medium transition-all duration-200 select-none cursor-pointer group text-left {{ $isActive 
                ? 'bg-orange-500/10 text-orange-600 dark:text-orange-400 font-semibold' 
                : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100/80 dark:hover:bg-gray-800/60' }}"
            :class="{ 'justify-center px-2': sidebarCollapsed }"
            @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; localStorage.setItem('sidebarCollapsed', false); } else { open = !open; }"
            :aria-expanded="open">

        @if($isActive)
            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-orange-500 rounded-r-full"
                  :class="{ 'left-0.5': sidebarCollapsed }"></span>
        @endif

        <span class="inline-flex items-center justify-center transition-transform duration-200 group-hover:scale-110 {{ $isActive ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 group-hover:text-gray-700 dark:text-gray-400 dark:group-hover:text-gray-200' }}"
              :class="{ 'mr-3': !sidebarCollapsed }">
            <i class="mdi {{ $icon }} text-xl leading-none"></i>
        </span>

        <span class="flex-1 truncate tracking-tight text-[13.5px]" x-show="!sidebarCollapsed" x-transition>
            {{ $title }}
        </span>

        @if(isset($badge) && $badge)
            <span class="ml-2 text-[11px] font-bold px-2 py-0.5 rounded-full {{ $badgeClass }}"
                  x-show="!sidebarCollapsed" x-transition>
                {{ $badge }}
            </span>
        @endif

        <i class="mdi mdi-chevron-down text-lg ml-auto transition-transform duration-200 text-gray-400"
           :class="{ 'rotate-180 text-orange-500': open }"
           x-show="!sidebarCollapsed" x-transition></i>
    </button>

    {{-- Collapsed Mode Tooltip --}}
    <div class="fixed left-20 ml-2 px-3 py-1.5 bg-gray-900/95 dark:bg-gray-800 backdrop-blur-md text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-150 z-50 hidden lg:block border border-gray-700/50"
         x-show="sidebarCollapsed"
         style="display: none;">
        {{ $title }}
    </div>

    {{-- Submenu Items with sleek tree guide line --}}
    <div x-show="open && !sidebarCollapsed" x-collapse>
        <ul class="mt-1 ml-5 pl-3 border-l border-gray-200 dark:border-gray-800 space-y-1">
            {{ $slot }}
        </ul>
    </div>
</li>