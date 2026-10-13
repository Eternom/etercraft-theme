@extends('layouts.base')

@section('title', trans('messages.home'))

@php
    // Discord vient des réseaux sociaux d'Azuriom : un seul endroit pour le régler
    $discord = social_links()->firstWhere('type', 'discord');

    // Images du hero : celles de la config du thème, sinon l'image de fond d'Azuriom ; ordre tiré au hasard
    $heroImages = config('theme.hero_images') ?: array_filter([setting('background')]);
    shuffle($heroImages);
    $heroLayout = in_array(config('theme.hero_layout'), ['cinematic', 'split'], true) ? config('theme.hero_layout') : 'centered';

    // Sections sous le hero, dans l'ordre choisi. Celles d'un plugin n'existent que si le plugin est actif ;
    // une section absente de la config (ajoutée par une mise à jour du thème) va à la fin.
    $plugins = ['vote', 'shop', 'wiki', 'forum'];
    $sections = array_values(array_filter(
        ['features', 'join', 'news', ...$plugins],
        fn (string $section) => ! in_array($section, $plugins, true) || plugins()->isEnabled($section),
    ));
    $order =array_values(array_unique(array_merge(array_intersect(config('theme.sections') ?? [], $sections), $sections)));
@endphp

@section('app')
    @include('elements.hero.'.$heroLayout)

    <div id="discover" class="container content">
        @include('elements.session-alerts')

        @if($message)
            <div class="glass-card p-4 mb-5" data-reveal>{{ $message }}</div>
        @endif
    </div>

    @foreach($order as $section)
        @include('elements.home.'.$section)
    @endforeach
@endsection
