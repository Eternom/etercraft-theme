{{-- Accueil du Wiki, habillé par le thème (remplace wiki::categories.index). Disposition au choix dans la config du thème. --}}
@extends('layouts.app')

@section('title', trans('wiki::messages.title'))

@php
    $layout = config('theme.wiki_layout', 'tiles');

    // Les tuiles n'affichent que le nombre de pages ; les autres dispositions listent les titres (sans charger le contenu)
    if ($layout === 'tiles') {
        $categories->loadCount('pages');
    } else {
        $categories->load('pages:id,category_id,title,slug,position');
    }
@endphp

@section('content')
    @include('wiki::partials._header', ['title' => trans('wiki::messages.title')])

    <div id="wiki">
        @include('elements.wiki.'.$layout, ['categories' => $categories])
    </div>
@endsection
