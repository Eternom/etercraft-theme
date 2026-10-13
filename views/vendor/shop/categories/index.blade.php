{{-- Accueil de la boutique, habillé par le thème (remplace shop::categories.index) --}}
@extends('layouts.app')

@section('title', trans('shop::messages.title'))

@section('content')
    <h1>{{ trans('shop::messages.title') }}</h1>

    <div class="row g-4" id="shop">
        <div class="col-lg-3">
            @include('shop::categories._sidebar')
        </div>

        <div class="col-lg-9">
            {{-- Message d'accueil réglé dans les paramètres du plugin (HTML autorisé par le plugin) --}}
            <div class="shop-welcome">
                {{ $welcome }}
            </div>

            @unless($categories->isEmpty())
                <div class="row g-3 mt-1">
                    @foreach($categories as $category)
                        <div class="col-sm-6 col-xl-4">
                            <a href="{{ route('shop.categories.show', $category) }}" class="wiki-tile" data-spotlight>
                                <i class="{{ $category->icon ?? 'bi bi-bag' }}" aria-hidden="true"></i>
                                <span>{{ $category->name }}</span>
                                <i class="bi bi-arrow-right wiki-tile-arrow" aria-hidden="true"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endunless
        </div>
    </div>
@endsection
