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
    $bannerPath =
        isset($configMap['banner_principal']) && !empty($configMap['banner_principal']->path_archive)
            ? asset('storage/' . $configMap['banner_principal']->path_archive)
            : asset('../img/banner.png');

@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content name="description" />
    <meta content name="author" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $nameApp }}</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <link href="/css/vendor.min.css" rel="stylesheet" />
    <link href="/css/app.min.css" rel="stylesheet" />

    <link href="/plugins/jvectormap-next/jquery-jvectormap.css" rel="stylesheet" />
    <link href="/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet" />
    <link href="/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>

</head>

<body class="pace-top">

    <div id="loader" class="app-loader">
        <span class="spinner"></span>
    </div>


    <div id="app" class="app">

        <div class="login login-with-news-feed">

            <div class="news-feed">

                <div class="news-image" style="background-image: url({{ $bannerPath }})">
                </div>
                <div class="news-caption">
                    <h4 class="caption-title"><b>{{ $nameApp }}</b> App</h4>
                    <p>
                        Download the {{ $nameApp }} app for iPhone®, iPad®, and Android™.
                    </p>
                </div>
            </div>


            <div class="login-container">

                <div class="login-header mb-30px">
                    <div class="brand">
                        <div class="d-flex align-items-center">
                            <img src="{{ $icon_app }}" alt="Logo" class="img-fluid rounded-circle"
                                style="width: 70px; height: 70px; margin:1rem;">
                            <!-- <span class="logo"></span> -->
                            <b>{{ $nameApp }}</b>
                        </div>
                        <small>The Best {{ $nameApp }} App</small>
                    </div>
                    <div class="icon">
                        <i class="fa fa-sign-in-alt"></i>
                    </div>
                </div>


                <div class="login-content">
                    @yield('content')
                    <hr class="bg-gray-600 opacity-2" />
                    <div class="text-gray-600 text-center  mb-0">
                        &copy; {{ $nameApp }} All Right Reserved {{ @date('Y') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="theme-panel">
            <a href="javascript:;" data-toggle="theme-panel-expand" class="theme-collapse-btn"><i
                    class="fa fa-cog"></i></a>
            <div class="theme-panel-content" data-scrollbar="true" data-height="100%">
                <h5>App Settings</h5>


                <div class="theme-panel-divider"></div>
                <div class="row mt-10px">
                    <div class="col-8 control-label text-dark fw-bold">
                        <div>Dark Mode <span class="badge bg-primary ms-1 py-2px position-relative"
                                style="top: -1px;">NEW</span>
                        </div>
                        <div class="lh-14">
                            <small class="text-dark opacity-50">
                                Adjust the appearance to reduce glare and give your eyes a break.
                            </small>
                        </div>
                    </div>
                    <div class="col-4 d-flex">
                        <div class="form-check form-switch ms-auto mb-0">
                            <input type="checkbox" class="form-check-input" name="app-theme-dark-mode"
                                id="appThemeDarkMode" value="1" />
                            <label class="form-check-label" for="appThemeDarkMode">&nbsp;</label>
                        </div>
                    </div>
                </div>
                <div class="theme-panel-divider"></div>

            </div>
        </div>
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top"
            data-toggle="scroll-to-top"><i class="fa fa-angle-up"></i></a>

    </div>



    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>

    <script src="/js/jquery.min.js"></script>
    <script src="/js/vendor.min.js" type="text/javascript"></script>
    <script src="/js/app.min.js" type="text/javascript"></script>
    <script src="/js/dashboard.js" type="text/javascript"></script>
    <script src="/plugins/jquery-sparkline/jquery.sparkline.min.js" type="text/javascript"></script>

</body>

</html>
