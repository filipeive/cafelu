@props(['title', 'collapsible' => false, 'id' => null])

@if($collapsible)
    <li class="nav-section collapsible">
        <a href="#{{ $id }}" data-bs-toggle="collapse" aria-expanded="true" class="section-header">
            <span class="nav-section-title">{{ $title }}</span>
            <i class="mdi mdi-chevron-down section-arrow"></i>
        </a>
        <div class="collapse show" id="{{ $id }}">
            <ul class="nav flex-column section-content">
                {{ $slot }}
            </ul>
        </div>
    </li>
@else
    <li class="nav-section">
        <span class="nav-section-title">{{ $title }}</span>
    </li>
@endif