@extends('layouts.base')

@section('title', trans('messages.home'))

@php
    // Contenu des sections : celui de la config du thème, sinon le texte par défaut du fichier de langue
    $features = config('theme.features') ?: trans('theme::messages.features.items');
    $joinSteps = config('theme.join_steps') ?: trans('theme::messages.join.steps');
    // Discord vient des réseaux sociaux d'Azuriom : un seul endroit pour le régler
    $discord = social_links()->firstWhere('type', 'discord');
@endphp

@section('app')
    <section class="hero">
        @if(setting('background'))
            <div class="hero-image" style="background-image: url('{{ image_url(setting('background')) }}'); --ec-shade: {{ config('theme.shade', 60) / 100 }}"></div>
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
                @if($discord)
                    <a href="{{ $discord->value }}" target="_blank" rel="noopener noreferrer" class="btn btn-glass btn-lg">
                        <i class="bi bi-discord"></i> Discord
                    </a>
                @endif
            </div>
        </div>

        <a href="#discover" class="hero-scroll" aria-label="{{ trans('theme::messages.hero.discover') }}">
            <i class="bi bi-chevron-down"></i>
        </a>
    </section>

    <div id="discover" class="container content">
        @include('elements.session-alerts')

        @if($message)
            <div class="glass-card p-4 mb-5" data-reveal>{{ $message }}</div>
        @endif
    </div>

    @if(config('theme.show_features', true))
        <section id="features" class="section">
            <div class="container">
                @include('elements.features.'.(in_array(config('theme.features_layout'), ['bento', 'showcase'], true) ? config('theme.features_layout') : 'list'), [
                    'title' => config('theme.features_title') ?: trans('theme::messages.features.title'),
                    'subtitle' => config('theme.features_subtitle') ?: trans('theme::messages.features.subtitle'),
                    'features' => array_values($features),
                ])
            </div>
        </section>
    @endif

    @if(config('theme.show_join', true))
    <section class="section">
        <div class="container">
            <div class="section-head text-center" data-reveal>
                <h2>{{ config('theme.join_title') ?: trans('theme::messages.join.title') }}</h2>
            </div>

            <div class="row g-4">
                @foreach(array_values($joinSteps) as $index => $step)
                    <div class="col-md-4" data-reveal>
                        <div class="glass-card step-card h-100">
                            <span class="step-number">{{ $index + 1 }}</span>
                            <h3>{{ $step['title'] }}</h3>
                            <p>{{ $step['text'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

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
