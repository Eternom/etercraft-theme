@extends('layouts.base')

@section('title', trans('messages.home'))

@php
    $features = [
        ['icon' => 'bi-hammer', 'key' => 'jobs'],
        ['icon' => 'bi-flag', 'key' => 'clans'],
        ['icon' => 'bi-stars', 'key' => 'pass'],
        ['icon' => 'bi-award', 'key' => 'success'],
        ['icon' => 'bi-globe2', 'key' => 'resource'],
        ['icon' => 'bi-trophy', 'key' => 'rankings'],
    ];
@endphp

@section('app')
    <section class="hero">
        @if(setting('background'))
            <div class="hero-image" style="background-image: url('{{ image_url(setting('background')) }}')"></div>
        @endif
        <div class="hero-glow"></div>

        <div class="container hero-content text-center">
            @if($server)
                <span class="status-pill" data-reveal>
                    @if($server->isOnline())
                        <span class="status-dot online"></span>
                        {{ trans_choice('theme::messages.hero.online', $server->getOnlinePlayers()) }}
                    @else
                        <span class="status-dot"></span>
                        {{ trans('theme::messages.hero.offline') }}
                    @endif
                </span>
            @endif

            <h1 class="hero-title" data-reveal>{{ site_name() }}</h1>

            @if(config('theme.title'))
                <p class="hero-lead" data-reveal>{{ config('theme.title') }}</p>
            @endif
            @if(config('theme.subtitle'))
                <p class="hero-text" data-reveal>{{ config('theme.subtitle') }}</p>
            @endif

            <div class="d-flex flex-column flex-sm-row justify-content-center gap-3 mt-4" data-reveal>
                @if($server)
                    <button type="button" class="btn btn-primary btn-lg btn-glow copy-ip"
                            data-copy="{{ $server->fullAddress() }}"
                            data-copied="{{ trans('theme::messages.hero.copied') }}">
                        <i class="bi bi-copy"></i>
                        <span class="copy-label">{{ $server->fullAddress() }}</span>
                    </button>
                @endif
                @if(Route::has('vote.home'))
                    <a href="{{ route('vote.home') }}" class="btn btn-glass btn-lg">
                        <i class="bi bi-hand-thumbs-up"></i> {{ trans('theme::messages.hero.vote') }}
                    </a>
                @endif
                @if(config('theme.discord'))
                    <a href="{{ config('theme.discord') }}" target="_blank" rel="noopener noreferrer" class="btn btn-glass btn-lg">
                        <i class="bi bi-discord"></i> Discord
                    </a>
                @endif
            </div>
        </div>

        <a href="#features" class="hero-scroll" aria-label="{{ trans('theme::messages.hero.discover') }}">
            <i class="bi bi-chevron-down"></i>
        </a>
    </section>

    <div class="container content">
        @include('elements.session-alerts')

        @if($message)
            <div class="glass-card p-4 mb-5" data-reveal>{{ $message }}</div>
        @endif
    </div>

    <section id="features" class="section">
        <div class="container">
            <div class="section-head text-center" data-reveal>
                <h2>{{ trans('theme::messages.features.title') }}</h2>
                <p>{{ trans('theme::messages.features.subtitle') }}</p>
            </div>

            <div class="row g-4">
                @foreach($features as $feature)
                    @php([$title, $text] = trans('theme::messages.features.'.$feature['key']))
                    <div class="col-md-6 col-lg-4" data-reveal>
                        <article class="glass-card feature-card h-100">
                            <span class="feature-icon"><i class="bi {{ $feature['icon'] }}"></i></span>
                            <h3>{{ $title }}</h3>
                            <p>{{ $text }}</p>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-head text-center" data-reveal>
                <h2>{{ trans('theme::messages.join.title') }}</h2>
            </div>

            <div class="row g-4">
                @foreach(trans('theme::messages.join.steps') as $index => [$title, $text])
                    <div class="col-md-4" data-reveal>
                        <div class="glass-card step-card h-100">
                            <span class="step-number">{{ $index + 1 }}</span>
                            <h3>{{ $title }}</h3>
                            <p>{{ $text }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if(! $posts->isEmpty())
        <section class="section">
            <div class="container">
                <div class="section-head text-center" data-reveal>
                    <h2>{{ trans('theme::messages.news.title') }}</h2>
                </div>

                <div class="row g-4">
                    @foreach($posts as $post)
                        <div class="col-md-6 col-lg-4" data-reveal>
                            <a href="{{ route('posts.show', $post) }}" class="glass-card post-card h-100">
                                @if($post->hasImage())
                                    <div class="post-image">
                                        <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}">
                                    </div>
                                @endif
                                <div class="p-4">
                                    <p class="post-date">{{ format_date($post->published_at) }}</p>
                                    <h3>{{ $post->title }}</h3>
                                    <p class="text-body-secondary mb-0">{{ Str::limit(strip_tags($post->content), 140) }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(Route::has('vote.home'))
        <section class="section pt-0">
            <div class="container">
                <div class="cta-card" data-reveal>
                    <div>
                        <h2>{{ trans('theme::messages.vote.title') }}</h2>
                        <p class="mb-0">{{ trans('theme::messages.vote.text') }}</p>
                    </div>
                    <a href="{{ route('vote.home') }}" class="btn btn-primary btn-lg btn-glow flex-shrink-0">
                        <i class="bi bi-hand-thumbs-up"></i> {{ trans('theme::messages.hero.vote') }}
                    </a>
                </div>
            </div>
        </section>
    @endif
@endsection
