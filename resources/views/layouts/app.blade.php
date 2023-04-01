<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <x-head.tinymce-config/>
</head>
<body class="bg-gray-100 h-screen antialiased leading-none font-sans">
    <div id="app">
        <header class="sticky top-0 bg-blue-700 py-6 z-10">
            <div class="container mx-auto flex justify-between items-center px-6">
                <div>
                    <a href="{{ url('/') }}" class="text-lg font-semibold text-gray-100 no-underline">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>
                <nav class=" space-x-4 text-gray-300 text-sm sm:text-base">
                    <a class="no-underline text-white font-bold py-2 px-4 rounded-full hover:bg-orange-400" href="/">Home</a>
                    <a class="no-underline text-white font-bold py-2 px-4 rounded-full hover:bg-orange-400" href="/blog">Blog</a>
                    @guest
                        <a class="no-underline text-white font-bold py-2 px-4 rounded-full hover:bg-orange-400" href="{{ route('login') }}">{{ __('Login') }}</a>
                        @if (Route::has('register'))
                            <a class="no-underline text-white font-bold py-2 px-4 rounded-full hover:bg-orange-400" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    @else
                        <span class='text-white py-2 px-4'>{{ Auth::user()->name }}</span>

                        <a href="{{ route('logout') }}"
                           class="no-underline text-white font-bold py-2 px-4 rounded-full hover:bg-orange-400"
                           onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            {{ csrf_field() }}
                        </form>
                    @endguest
                </nav>
            </div>
        </header>

        <div>
            @yield('content')
        </div>

        <div>
            @include('layouts.footer')
        </div>
    </div>
</body>
</html>