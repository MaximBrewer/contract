<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="server-time" content="{{ date(DATE_ATOM) }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="http://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="http://cdn.materialdesignicons.com/2.5.94/css/materialdesignicons.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}?ver=3.52" rel="stylesheet">
    <script>
        window._translations = {!! cache('translations'); !!};
    </script>
    <style>
        .navbar-brand {
            line-height: 1
        }

        .navbar-brand small {
            font-size: .7em
        }
    </style>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
</head>

<body class="d-flex flex-column">
    <div id="app">
        <flash-message class="flashpool flash__wrapper"></flash-message>
        <nav class="navbar  navbar-expand-lg navbar-dark flex-column flex-md-row  bg-dark">
            <div class="container">
                <a class="navbar-brand" href="https://www.cross-contract.ru">о
                    Cross-Contract.ru<br><small>8(495)144-53-34</small></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a id="loginDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                @guest
                                {{ $kinds[0]->title }} <span class="caret"></span>
                                @else
                                {{ Auth::user()->kind->title }} <span class="caret"></span>
                                @endguest

                            </a>
                            <div class="dropdown-menu dropdown-menu-left" aria-labelledby="loginDropdown">
                                @foreach($kinds as $kind)
                                @guest
                                <a class="dropdown-item" href="javascript:void(0)">{{ $kind->title }}</a>
                                @else
                                <a class="dropdown-item" href="/personal?kind={{ $kind->id }}">{{ $kind->title }}</a>
                                @endguest
                                @endforeach
                            </div>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" href="//cross-contract.ru">{{ __('About project') }}</a>
                        </li> --}}
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        @hasSection('loginform'))
                        <li class="nav-item dropdown">
                            <a id="loginDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ __('Login') }} <span class="caret"></span>
                            </a>
                            <div id="loginDropdownMenu" class="dropdown-menu dropdown-menu-left" aria-labelledby="loginDropdown" onclick="event.stopPropagation();">
                                @yield('loginform')
                            </div>

                        </li>
                        @else


                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif
                        @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item">
                            <a href="/personal/auctions/show/{{ config('app.test_auction') }}" class="nav-link" :to="{name: 'showAuction', props: {id: {{ config('app.test_auction') }}}}">{{ __('Test auction & chat') }}
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="sendmessageDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ __('Send Message') }} <span class="caret"></span>
                            </a>
                            <div id="sendmessageDropdownMenu" class="dropdown-menu dropdown-menu-right" aria-labelledby="sendmessageDropdown" onclick="event.stopPropagation();">
                                <send-message></send-message>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ __('Contragents') }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <router-link :to="{name: 'contragentIndex'}" class="dropdown-item">
                                    {{ __('All contragents') }}</router-link>
                                <router-link :to="{name: 'createContragent'}" class="dropdown-item">
                                    {{ __('Create new contragent') }}</router-link>
                                <router-link :to="{name: 'reviews'}" class="dropdown-item">{{ __('My reviews') }}
                                </router-link>
                                <router-link :to="{name: 'disputesIndex'}" class="dropdown-item">
                                    {{ __('Disputes') }}</router-link>
                                <div class="dropdown-divider"></div>
                                <div class="dropdown-header"><h5>Договоры</h5></div>
                                <router-link :to="{name: 'contracts'}" class="dropdown-item">{{ __('Предложенные мной') }}
                                </router-link>
                                <router-link :to="{name: 'contractsReciever'}" class="dropdown-item">{{ __('Предложенные мне') }}
                                </router-link>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ __('Auctions') }} <span class="caret"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <router-link :to="{name: 'auctionIndex'}" class="dropdown-item">{{ __('All auctions') }}
                                </router-link>
                                <router-link :to="{name: 'auctionMy'}" class="dropdown-item">{{ __('My auctions') }}
                                </router-link>
                                <router-link :to="{name: 'auctionBid'}" class="dropdown-item">{{ __('Bidder') }}
                                </router-link>
                                <router-link :to="{name: 'indexTarget'}" class="dropdown-item">
                                    {{ __('All targets (tenders)') }}</router-link>
                                <router-link :to="{name: 'auctionArchive'}" class="dropdown-item">{{ __('Archive') }}
                                </router-link>
                                <router-link :to="{name: 'logistics'}" class="dropdown-item">{{ __('Logistics') }}
                                </router-link>
                                <router-link :to="{name: 'finance'}" class="dropdown-item">{{ __('Посредники') }}
                                </router-link>
                                <router-link :to="{name: 'joints'}" class="dropdown-item">{{ __('Совместные закупки') }}
                                </router-link>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                                <router-link :to="{name: 'dialoguesIndex'}" class="dropdown-item">{{ __('Messages') }}
                                </router-link>

                                <router-link :to="{name: 'company'}" class="dropdown-item">{{ __('Settings') }}
                                </router-link>

                                <router-link :to="{name: 'staff'}" class="dropdown-item">{{ __('Staff') }}
                                </router-link>

                                <router-link :to="{name: 'myResults'}" class="dropdown-item">{{ __('My results') }}
                                </router-link>

                                <router-link :to="{name: 'jointPurchases'}" class="dropdown-item">{{ __('Совместные закупки') }}
                                </router-link>

                                <router-link :to="{name: 'historyPurchases'}" class="dropdown-item">{{ __('История закупок') }}
                                </router-link>

                                
                                
                                <router-link :to="{name: 'settlementsIndex'}" class="dropdown-item">{{ __('Mutual Settlements') }}
                                </router-link>

                                <a class="dropdown-item" href="javascript:void(0)" onclick="document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="py-4">
            <div class="wrapper">
                @yield('content')
            </div>
        </main>
    </div>
    <!-- Footer -->
    <footer id="sticky-footer" class="py-4 bg-dark text-white-50">
        <div class="container text-center">
            <small>&copy; 2023 cross-contract.ru</small>
        </div>
    </footer>
    <!-- Footer -->
    @include ('footer')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}?ver=3.52" defer></script>
    <script src="//code.jivosite.com/widget/SgKrZgYDyo" async></script>
</body>

</html>