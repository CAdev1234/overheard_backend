<!doctype html>
<html class="header-dark sidebar-left-big-icons">
<head>

    <!-- Basic -->
    <meta charset="UTF-8">

    <title>Overheard | @yield('title')</title>
    <meta name="keywords" content="Overheard" />
    <meta name="description" content="Overheard">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Web Fonts  -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/bootstrap.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/animate/animate.css')}}">

    <link rel="stylesheet" href="{{asset('assets/vendor/font-awesome/css/font-awesome.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/magnific-popup/magnific-popup.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}" />
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    @yield('specific page vendor css')

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/theme.css')}}" />

    <!-- Skin CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/skins/default.css')}}" />

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

    <!-- Head Libs -->
    <script src="{{asset('assets/vendor/modernizr/modernizr.js')}}"></script>
    <script>
        let app_url = '{{ url('/') }}';
    </script>

</head>
<body>
<section class="body">

    <!-- start: header -->
    <header class="header">
        <div class="logo-container">
            <a href="{{ url('/') }}" class="logo">
                {{--<img src="{{asset('assets/img/logo.png')}}" width="200" height="100" alt="Porto Admin" />--}}
                <h2 class="main-header-title">Overheard</h2>
            </a>
            <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
                <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
            </div>
        </div>

        <!-- start: search & user box -->
        <div class="header-right">

            <span class="separator"></span>

            <div id="userbox" class="userbox">
                <a href="#" data-toggle="dropdown">
                    <figure class="profile-picture">
                        @if(Auth::user()->avatar != null)
                            <img src="{{asset('assets/img/avatars/avatar.png')}}" alt="User Avatar" class="rounded-circle" data-lock-picture="img/!logged-user.jpg" />
                        @else
                            <img src="{{asset('assets/img/avatars/avatar.png')}}" alt="User Avatar" class="rounded-circle" data-lock-picture="img/!logged-user.jpg" />
                        @endif

                    </figure>
                    <div class="profile-info">
                        @guest
                            <span class="name"></span>
                        @else
                            <span class="name">{{ Auth::user()->name }}</span>
                        @endguest
                    </div>

                    <i class="fa custom-caret"></i>
                </a>

                <div class="dropdown-menu">
                    <ul class="list-unstyled mb-2">
                        <li class="divider"></li>
                        <li>
                            <a role="menuitem" tabindex="-1" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="fa fa-power-off"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- end: search & user box -->
    </header>
    <!-- end: header -->

    <div class="inner-wrapper">
        <!-- start: sidebar -->
        <aside id="sidebar-left" class="sidebar-left">

            <div class="sidebar-header">
                <div class="sidebar-title">
                    Navigation
                </div>
                <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
                    <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                </div>
            </div>

            <div class="nano">
                <div class="nano-content">
                    <nav id="menu" class="nav-main" role="navigation">
                        <ul class="nav nav-main">
                            <li>
                                <a class="nav-link" href={{route('usermanagement')}}>
                                    <i class="fas fa-user-friends" aria-hidden="true"></i>
                                    <span class="left-sidebar-nav-text">User Management</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-main">
                            <li>
                                <a class="nav-link" href="{{route('communitymanagement')}}">
                                    <i class="fas fa-users" aria-hidden="true"></i>
                                    <span class="left-sidebar-nav-text">Communities</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-main">
                            <li>
                                <a class="nav-link" href="{{route('reportermanagement')}}">
                                    <i class="fas fa-user-check" aria-hidden="true"></i>
                                    <span class="left-sidebar-nav-text">Reporters</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-main">
                            <li>
                                <a class="nav-link" href="{{route('reportmanagement')}}">
                                    <i class="far fa-sticky-note" aria-hidden="true"></i>
                                    <span class="left-sidebar-nav-text">Reports</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-main">
                            <li>
                                <a class="nav-link" href="{{route('withdrawalmanagement')}}">
                                    <i class="fas fa-money-bill-wave" aria-hidden="true"></i>
                                    <span class="left-sidebar-nav-text">Withdrawals</span>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav nav-main">
                            <li>
                                <a class="nav-link" href="{{route('advertisementmanagement')}}">
                                    <i class="fab fa-adversal" aria-hidden="true"></i>
                                    <span class="left-sidebar-nav-text">Advertisement Settings</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <script>
                    // Maintain Scroll Position
                    if (typeof localStorage !== 'undefined') {
                        if (localStorage.getItem('sidebar-left-position') !== null) {
                            var initialPosition = localStorage.getItem('sidebar-left-position'),
                                sidebarLeft = document.querySelector('#sidebar-left .nano-content');

                            sidebarLeft.scrollTop = initialPosition;
                        }
                    }
                </script>


            </div>

        </aside>
        <!-- end: sidebar -->

        @yield('content body')
    </div>

</section>
<!-- Vendor -->
<script src="{{asset('assets/vendor/jquery/jquery.js')}}"></script>
<script src="{{asset('assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js')}}"></script>
<script src="{{asset('assets/vendor/popper/umd/popper.min.js')}}"></script>
<script src="{{asset('assets/vendor/bootstrap/js/bootstrap.js')}}"></script>
<script src="{{asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('assets/vendor/common/common.js')}}"></script>
<script src="{{asset('assets/vendor/nanoscroller/nanoscroller.js')}}"></script>
<script src="{{asset('assets/vendor/magnific-popup/jquery.magnific-popup.js')}}"></script>
<script src="{{asset('assets/vendor/jquery-placeholder/jquery-placeholder.js')}}"></script>


@yield('specific page vendor js')

<!-- Theme Base, Components and Settings -->
<script src="{{asset('assets/js/theme.js')}}"></script>

<!-- Theme Custom -->
<script src="{{asset('assets/js/custom.js')}}"></script>

<!-- Theme Initialization Files -->
<script src="{{asset('assets/js/theme.init.js')}}"></script>

@yield('page js')

</body>
</html>