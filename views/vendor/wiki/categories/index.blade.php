{{-- Accueil du Wiki, habillé par le thème (remplace wiki::categories.index) --}}
@extends('layouts.app')

@section('title', trans('wiki::messages.title'))

@section('content')
    @include('wiki::partials._header', ['title' => trans('wiki::messages.title')])

    <div id="wiki">
        @include('elements.wiki-tiles', ['categories' => $categories])
    </div>
@endsection
