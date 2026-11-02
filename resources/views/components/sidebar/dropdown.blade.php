@props(['icon', 'title', 'id', 'badge' => null, 'badgeClass' => 'bg-gray-100 text-gray-700'])

@php
    $content = $slot->toHtml();
    $isActive = strpos($content, 'bg-orange-500/10') !== false || strpos($content, 'font-semibold') !== false || strpos($content, 'text-orange-600') !== false;
@endphp

<li class="relative mb-0.5 group" x-data="{ open: {{ $isActive ? 'true' : 'false' }} }">
    <button type="button"
            class="relative flex items-center w-full px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150 select-none cursor-pointer group text-left {{ $isActive 
                ? 'bg-accent text-accent-foreground font-semibold' 
                : 'text-muted-foreground hover:text-foreground hover:bg-accent/60' }}"
            :class="{ 'justify-center px-2': sidebarCollapsed }"
            @click="if(sidebarCollapsed) { sidebarCollapsed = false; open = true; localStorage.setItem('sidebarCollapsed', false); } else { open = !open; }"
            :aria-expanded="open">

        @if($isActive)
            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-primary rounded-r-full"
                  :class="{ 'left-0.5': sidebarCollapsed }"></span>
        @endif

        <span class="inline-flex items-center justify-center transition-transform duration-150 group-hover:scale-105 {{ $isActive ? 'text-primary' : 'text-muted-foreground group-hover:text-foreground' }}"
              :class="{ 'mr-3': !sidebarCollapsed }">
            <i class="mdi {{ $icon }} text-xl leading-none"></i>
        </span>

        <span class="flex-1 truncate tracking-tight text-[13px]" x-show="!sidebarCollapsed" x-transition>
            {{ $title }}
        </span>

        @if(isset($badge) && $badge)
            <span class="ml-2 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $badgeClass }}"
                  x-show="!sidebarCollapsed" x-transition>
                {{ $badge }}
            </span>
        @endif

        <i class="mdi mdi-chevron-down text-base ml-auto transition-transform duration-150 text-muted-foreground"
           :class="{ 'rotate-180 text-primary': open }"
           x-show="!sidebarCollapsed" x-transition></i>
    </button>

    {{-- Collapsed Mode Tooltip --}}
    <div class="fixed left-20 ml-2 px-3 py-1.5 bg-popover text-popover-foreground text-xs font-medium rounded-lg shadow-md whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-150 z-50 hidden lg:block border border-border"
         x-show="sidebarCollapsed"
         style="display: none;">
        {{ $title }}
    </div>

    {{-- Submenu Items with sleek tree guide line --}}
    <div x-show="open && !sidebarCollapsed" x-collapse>
        <ul class="mt-1 ml-5 pl-3 border-l border-border/70 flex flex-col gap-0.5">
            {{ $slot }}
        </ul>
    </div>
</li>