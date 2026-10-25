@props([
    'route' => null,
    'url' => null,
    'icon' => '',
    'title' => '',
    'badge' => null,
    'badgeClass' => null,
    'badgePrefix' => '',
    'showBadge' => true,
    'external' => false
])

@php
    $isActive = false;
    if ($url) {
        $isActive = request()->fullUrlIs($url);
    } elseif ($route && $route !== '#') {
        $isActive = request()->routeIs($route) || request()->routeIs($route . '.*');
    }
    
    $href = $url ?? ($external ? $route : ($route && $route !== '#' && Route::has($route) ? route($route) : '#'));
@endphp

<li class="relative mb-1 group" x-data>
    <a href="{{ $href }}"
       @if($external) target="_blank" @endif
       class="relative flex items-center px-3 py-2 rounded-xl text-sm font-medium transition-all duration-200 select-none {{ $isActive 
            ? 'bg-orange-500/10 text-orange-600 dark:text-orange-400 font-semibold shadow-xs' 
            : 'text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100/80 dark:hover:bg-gray-800/60' }}"
       :class="{ 'justify-center px-2': sidebarCollapsed }">
       
        {{-- Active bar indicator --}}
        @if($isActive)
            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-orange-500 rounded-r-full"
                  :class="{ 'left-0.5': sidebarCollapsed }"></span>
        @endif

        {{-- Icon --}}
        <span class="inline-flex items-center justify-center transition-transform duration-200 group-hover:scale-110 {{ $isActive ? 'text-orange-500 dark:text-orange-400' : 'text-gray-400 group-hover:text-gray-700 dark:text-gray-400 dark:group-hover:text-gray-200' }}"
              :class="{ 'mr-3': !sidebarCollapsed }">
            <i class="mdi {{ $icon }} text-xl leading-none"></i>
        </span>

        {{-- Title --}}
        <span class="flex-1 truncate tracking-tight text-[13.5px]" x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            {{ $title }}
        </span>

        {{-- Badge --}}
        @if(isset($badge) && $showBadge && $badge !== null && $badge !== '')
            <span class="ml-auto text-[11px] font-bold px-2 py-0.5 rounded-full tracking-tight transition-all duration-150 shadow-2xs {{ $badgeClass ?? ($isActive ? 'bg-orange-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200') }}"
                  x-show="!sidebarCollapsed" 
                  x-transition>
                {{ $badgePrefix }}{{ $badge }}
            </span>
        @endif

        {{-- Custom Slot Badge or Extra Content --}}
        @if(isset($slot) && !empty(trim($slot->toHtml())))
            <span class="ml-auto" x-show="!sidebarCollapsed" x-transition>
                {{ $slot }}
            </span>
        @endif
    </a>

    {{-- Collapsed Mode Tooltip --}}
    <div class="fixed left-20 ml-2 px-3 py-1.5 bg-gray-900/95 dark:bg-gray-800 backdrop-blur-md text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-150 z-50 hidden lg:block border border-gray-700/50"
         x-show="sidebarCollapsed"
         style="display: none;">
        <div class="flex items-center gap-2">
            <span>{{ $title }}</span>
            @if(isset($badge) && $showBadge && $badge !== null && $badge !== '')
                <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ $badgeClass ?? 'bg-orange-500 text-white' }}">
                    {{ $badgePrefix }}{{ $badge }}
                </span>
            @endif
        </div>
    </div>
</li>