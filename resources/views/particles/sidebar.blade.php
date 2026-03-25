<div id="sidebar" class="app-sidebar">

    <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">

        <div class="menu">
            <div class="menu-profile">
                <a href="javascript:;" class="menu-profile-link" data-toggle="app-sidebar-profile"
                    data-target="#appSidebarProfileMenu">
                    @php
                        $coverSideUser =
                            isset($configMap['cover-sidebar-user']) &&
                            !empty($configMap['cover-sidebar-user']->path_archive)
                                ? asset('storage/' . $configMap['cover-sidebar-user']->path_archive)
                                : '../img/cover-sidebar-user.jpg';

                        $description_user =
                            isset($configMap['description_user']) && !empty($configMap['description_user']->value)
                                ? $configMap['description_user']->value
                                : '';
                    @endphp
                    <div class="menu-profile-cover with-shadow" style="background-image: url({{ $coverSideUser }})">
                    </div>
                    <div class="menu-profile-image">
                        <img src="{{ $icon_user_default }}" alt />
                    </div>
                    <div class="menu-profile-info">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                {{ Auth::user()->name }}
                            </div>
                            {{-- <div class="menu-caret ms-auto"></div> --}}
                        </div>
                        <small>{{ $description_user }}</small>
                    </div>
                </a>
            </div>

            <div class="menu-search mb-n3">
                <input type="text" class="form-control" placeholder="Pesquise aqui..." data-sidebar-search="true" />
            </div>

            <div class="menu-header">Menu do Sistema</div>

            @php
                $menus = App\Models\MenuSideBar::where('active', true)
                    ->orderBy('order', 'asc')
                    ->get()
                    ->groupBy('level');

                $topLevelMenus = $menus->get(0, collect()); // Menus de nível 0 (principais)
                $subMenusGrouped = $menus->get(1, collect())->groupBy('menu_above'); // Submenus agrupados por 'menu_above'
            @endphp

            @foreach ($topLevelMenus as $value)
                @if (Auth::user()->canAny(explode(',', $value->acl)))
                    @php
                        // Encontrar submenus para o menu atual
                        $subMenus = $subMenusGrouped->get($value->description, collect());
                    @endphp

                    @if ($subMenus->isNotEmpty())
                        <div class="menu-item has-sub">
                            <a href="javascript:;" class="menu-link" style="{{ $value->style }}">
                                <div class="menu-icon">
                                    <i class="fa {{ $value->icon }}"></i>
                                </div>
                                <div class="menu-text">{{ $value->description }}</div>
                                <div class="menu-caret"></div>
                            </a>

                            <div class="menu-submenu">
                                @foreach ($subMenus as $v)
                                    @if (Auth::user()->canAny(explode(',', $v->acl)))
                                        <div class="menu-item">
                                            <a href="{{ url($v->route) }}" class="menu-link"
                                                style="{{ $v->style }}">
                                                <div class="menu-icon">
                                                    <i class="fa {{ $v->icon }}"></i>
                                                </div>
                                                <div class="menu-text">{{ $v->description }}</div>
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="menu-item">
                            <a href="{{ url($value->route) }}" class="menu-link" style="{{ $value->style }}">
                                <div class="menu-icon">
                                    <i class="fa {{ $value->icon }}"></i>
                                </div>
                                <div class="menu-text">{{ $value->description }}</div>
                            </a>
                        </div>
                    @endif
                @endif

            @endforeach

            <div class="menu-item d-flex">
                <a href="javascript:;"
                    class="app-sidebar-minify-btn ms-auto d-flex align-items-center text-decoration-none"
                    data-toggle="app-sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
            </div>

        </div>

    </div>

</div>

<div class="app-sidebar-bg"></div>
<div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a>
</div>
