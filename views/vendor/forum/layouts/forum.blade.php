{{--
    Gabarit des pages du forum, habillé par le thème (remplace forum::layouts.forum).
    Options passées par les vues du plugin : $includeTitle (titre de page), $withSearch (recherche, champ « search »).
--}}
@extends('layouts.app')

@section('content')
    @include('forum::elements.nav')

    @if(($withSearch ?? false) || ($includeTitle ?? true))
        <div class="section-head-split mb-4">
            @if($includeTitle ?? true)
                <h1 class="mb-0">@yield('title')</h1>
            @endif

            @if($withSearch ?? false)
                <form class="wiki-search" method="GET" role="search">
                    <i class="bi bi-search" aria-hidden="true"></i>
                    <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="{{ trans('messages.actions.search') }}" aria-label="{{ trans('messages.actions.search') }}">
                </form>
            @endif
        </div>
    @endif

    @yield('forum')
@endsection
