{{-- For submenu --}}
<ul class="menu-content">
    @if (isset($menu))
        @foreach ($menu as $submenu)
            @php
                $submenuTranslation = '';
                if (isset($menu->i18n)) {
                    $submenuTranslation = $menu->i18n;
                }
            @endphp
            @can($submenu->permission)
                <li class="{{ request()->is($submenu->url) || request()->is($submenu->url . '/*') ? 'active' : '' }}">
                    <a href="{{ url('/') . '/' . $submenu->url }}" @if (isset($submenu->target)) target="{{ $submenu->target }}" @endif>
                        <i class="{{ isset($submenu->icon) ? $submenu->icon : '' }}"></i>
                        <span class="menu-title" data-i18n="{{ $submenuTranslation }}">{{ __($submenu->name) }}</span>
                    </a>
                    @if (isset($submenu->submenu))
                        @include('panels/submenu', ['menu' => $submenu->submenu])
                    @endif
                </li>
            @endcan
        @endforeach
    @endif
</ul>
