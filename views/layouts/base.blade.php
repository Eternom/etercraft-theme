<!DOCTYPE html>
@include('elements.base')
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', setting('description', ''))">
    <meta name="theme-color" content="#0c0a09">

    <meta property="og:title" content="@yield('title')">
    <meta property="og:type" content="@yield('type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ favicon() }}">
    <meta property="og:description" content="@yield('description', setting('description', ''))">
    <meta property="og:site_name" content="{{ site_name() }}">
    @stack('meta')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>document.documentElement.classList.add('js-reveal')</script>

    <title>@yield('title') | {{ site_name() }}</title>

    <link rel="shortcut icon" href="{{ favicon() }}">

    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('vendor/axios/axios.min.js') }}" defer></script>
    <script src="{{ asset('js/script.js') }}" defer></script>
    <script src="{{ theme_asset('js/theme.js') }}" defer></script>
    @stack('scripts')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600|outfit:500,600,700&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/base.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('css/style.css') }}" rel="stylesheet">
    @stack('styles')
    @include('elements.theme-color', ['color' => config('theme.color', '#FF7A00')])
</head>

<body>
@if(setting('background') && config('theme.background_pages', true) && ! request()->routeIs('home'))
    <div class="page-background" style="background-image: url('{{ image_url(setting('background')) }}')"></div>
@endif

<div id="app" class="flex-shrink-0">
    <header>
        @include('elements.navbar')
    </header>

    @yield('app')
</div>

<footer class="site-footer mt-auto">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-5">
                <a href="{{ route('home') }}" class="footer-brand">{{ site_name() }}</a>
                <p class="text-body-secondary mt-3 mb-0">{{ setting('description') ?: trans('theme::messages.footer.tagline') }}</p>
            </div>

            <div class="col-6 col-lg-3">
                <p class="footer-title">{{ trans('theme::messages.footer.links') }}</p>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ route('home') }}">{{ trans('messages.home') }}</a></li>
                    @if(Route::has('vote.home'))
                        <li><a href="{{ route('vote.home') }}">{{ trans('theme::messages.hero.vote') }}</a></li>
                    @endif
                    @if(Route::has('wiki.index'))
                        <li><a href="{{ route('wiki.index') }}">Wiki</a></li>
                    @endif
                    @if(Route::has('shop.home'))
                        <li><a href="{{ route('shop.home') }}">{{ trans('theme::messages.footer.shop') }}</a></li>
                    @endif
                    @foreach(config('theme.footer_links') ?? [] as $link)
                        <li><a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer">{{ $link['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="col-6 col-lg-4">
                <p class="footer-title">{{ trans('theme::messages.footer.community') }}</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach(social_links() as $link)
                        <a href="{{ $link->value }}" title="{{ $link->title }}" target="_blank" rel="noopener noreferrer" class="social-link">
                            <i class="{{ $link->icon }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container py-3 d-flex flex-column flex-md-row justify-content-between gap-2 small text-body-secondary">
            <span>{{ str_replace('{year}', date('Y'), setting('copyright')) }} · @lang('messages.copyright')</span>
            @if(config('theme.show_mojang', true))
                <span>{{ trans('theme::messages.footer.mojang') }}</span>
            @endif
        </div>
    </div>
</footer>

@stack('footer-scripts')

</body>
</html>
