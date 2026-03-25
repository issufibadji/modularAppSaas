@php
    // Faz uma única consulta para buscar todas as configurações desejadas
    $configs = \App\Models\AppConfig::where('id', '>', '0')->get();

    // Cria um array associativo para mapear as configurações pelas chaves
    $configMap = [];
    foreach ($configs as $config) {
        $configMap[$config->key] = $config;
    }

    $nameApp =
        isset($configMap['app_name']) && !empty($configMap['app_name']->value)
            ? $configMap['app_name']->value
            : config('app.name', 'Laravel');
    $icon_app =
        isset($configMap['icon_app']) && !empty($configMap['icon_app']->path_archive)
            ? asset('storage/' . $configMap['icon_app']->path_archive)
            : asset('img/logo.ico');
    $icon_user_default =
        isset($configMap['icon_user_default']) && !empty($configMap['icon_user_default']->path_archive)
            ? asset('storage/' . $configMap['icon_user_default']->path_archive)
            : asset('img/user.jpg');
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content name="description" />
    <meta content name="author" />
    <link rel="manifest" href="/manifest.json">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $nameApp }}</title>

    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="//fonts.bunny.net"> --}}
    {{-- <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet"> --}}

    {{-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" /> --}}

    <!-- CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <link href="{{ asset('css/vendor.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('plugins/jvectormap-next/jquery-jvectormap.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/gritter/css/jquery.gritter.css') }}" rel="stylesheet" />

    <style>
        .app-header {
            height: 50px !important;
        }

        body {
            font-size: 0.9em;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        @media only screen and (max-width: 768px) {

            #dropdown-notifications {
                position: absolute !important;
                inset: 0px 0px auto auto !important;
                margin: 0px !important;
                transform: translate3d(50px, 53px, 0px) !important;
            }
        }
    </style>
    @stack('style')

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/vendor.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/app.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dashboard.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>

    <script src="{{ asset('plugins/flot/source/jquery.canvaswrapper.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.colorhelpers.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.saturated.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.browser.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.drawSeries.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.uiConstants.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.time.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.resize.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.pie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.crosshair.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.categories.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.navigate.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.touchNavigate.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.hover.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.touch.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.selection.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.symbol.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/flot/source/jquery.flot.legend.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-sparkline/jquery.sparkline.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jvectormap-next/jquery-jvectormap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jvectormap-content/world-mill.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}" type="text/javascript">
    </script>

    <script src="{{ asset('service-worker.js') }}"></script>

</head>

<body>
    <div id="loader" class="app-loader">
        <span class="spinner"></span>
    </div>

    <div id="app" class="app app-header-fixed app-sidebar-fixed ">

        <div id="header" class="app-header">

            <div class="navbar-header">
                <a href="/home" class="navbar-brand">
                    <img src="{{ $icon_app }}" alt="Logo" class="img-fluid rounded-circle"
                        style="width: 30px; height: 30px; margin:1rem;">
                    {{ $nameApp }}
                </a>
                <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="navbar-nav">
                <div class="navbar-item dropdown" style="height: -webkit-fill-available">
                    <a href="#" data-bs-toggle="dropdown" class="navbar-link dropdown-toggle icon">
                        <i class="fa fa-bell"></i>
                        <span class="badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                    </a>
                    <div id='dropdown-notifications' class="dropdown-menu media-list dropdown-menu-end">
                        @if (auth()->user()->unreadNotifications->count() > 0)
                            <div class="dropdown-header">NOTIFICAÇÕES
                                ({{ auth()->user()->unreadNotifications->count() }})</div>
                        @endif
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <a href="{{ $notification->data['url'] ? $notification->data['url'] : 'javascript:;' }}"
                                class="dropdown-item media notification-link" data-id="{{ $notification->id }}"
                                target="_blank">
                                <div class="media-left">
                                    <i
                                        class="fa {{ $notification->data['icon'] ? $notification->data['icon'] : 'media-object' }} media-object bg-gray-500"></i>
                                </div>
                                <div class="media-body">
                                    <h6 class="media-heading">{{ $notification->data['title'] }} </h6>
                                    <p class="media-heading fs-11px fw-normal">{{ $notification->data['message'] }}
                                    </p>
                                    <div class="text-muted fs-10px">{{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </a>
                        @empty
                            <span class="dropdown-item">Nenhuma notificação</span>
                        @endforelse

                        @if (auth()->user()->unreadNotifications->count())
                            <a href="{{ route('notifications.markAllRead') }}" class="dropdown-item media" >Marcar todas como lidas</a>
                        @endif
                        {{-- <div class="dropdown-footer text-center">
                            <a href="javascript:;" class="text-decoration-none">View more</a>
                        </div> --}}
                    </div>
                </div>
                <div class="navbar-item navbar-user dropdown">
                    <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center"
                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-boundary="window"
                        data-bs-reference="parent">
                        <img src="{{ $icon_user_default }}" alt />
                        <span>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end me-1">
                        {{-- <a href="extra_profile.html" class="dropdown-item">Edit Profile</a>
                        <a href="settings.html" class="dropdown-item">Settings</a> --}}
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>

                    </div>
                </div>
            </div>

        </div>

        @include('particles.sidebar')

        <div id="content" class="app-content">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}

                    <a href="#" class="close" data-bs-dismiss="alert" aria-label="close"
                        style="float: right">&times;</a>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}

                    <a href="#" class="close" data-bs-dismiss="alert" aria-label="close"
                        style="float: right">&times;</a>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}

                    <a href="#" class="close" data-bs-dismiss="alert" aria-label="close"
                        style="float: right">&times;</a>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {!! implode('', $errors->all('<div>:message</div>')) !!}

                    <a href="#" class="close" data-bs-dismiss="alert" aria-label="close"
                        style="float: right">&times;</a>
                </div>
            @endif

            @yield('content')
        </div>

        @include('particles.themepanel')

        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top"
            data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>

    </div>

    <script>
        $(document).ready(function() {
            $('.notification-link').click(function(e) {
                e.preventDefault(); // Evita o comportamento padrão do clique

                var notificationId = $(this).data('id');
                var url = $(this).attr('href');

                // Marcar notificação como lida via AJAX
                $.ajax({
                    url: '{{ route('notifications.read') }}',
                    method: 'POST',
                    data: {
                        id: notificationId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Abre o link em uma nova guia
                            window.open(url, '_blank');
                        }
                    }
                });
            });
        });
    </script>
    @stack('scripts')

</body>

</html>
