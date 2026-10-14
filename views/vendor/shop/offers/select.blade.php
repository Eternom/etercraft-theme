{{--
    Rechargement : choix du montant, habillé par le thème (remplace shop::offers.select), dans la disposition de la
    boutique (cartes, liste ou grille tarifaire). Un clic envoie #submitForm (POST) vers l'offre choisie, comme dans
    le plugin. Le bonus se calcule par rapport à la recharge qui donne le moins de points par euro.
--}}
@extends('layouts.app')

@section('title', trans('shop::messages.offers.amount'))

@push('footer-scripts')
    <script>
        document.querySelectorAll('.payment-method').forEach(function (el) {
            el.addEventListener('click', function (ev) {
                ev.preventDefault();

                const form = document.getElementById('submitForm');
                form.action = el.href;
                form.submit();
            });
        });
    </script>
@endpush

@php
    $layout = in_array(config('theme.shop_layout'), ['list', 'pricing'], true) ? config('theme.shop_layout') : 'cards';
    $baseRate = $offers->min(fn ($offer) => $offer->money / max($offer->price, 0.01));
    $bonus = fn ($offer) => $baseRate > 0 ? (int) round(($offer->money / max($offer->price, 0.01)) / $baseRate * 100 - 100) : 0;
    $best = $offers->count() > 1 ? $offers->sortByDesc($bonus)->first() : null;
@endphp

@section('content')
    <h1>{{ trans('shop::messages.offers.amount') }}</h1>

    @if($offers->isEmpty())
        <p class="text-body-secondary"><i class="bi bi-exclamation-circle"></i> {{ trans('shop::messages.offers.empty') }}</p>
    @elseif($layout === 'list')
        <div class="package-list">
            @foreach($offers as $offer)
                <a href="{{ route('shop.offers.pay', [$offer->id, $gateway->type]) }}" class="payment-method package-row">
                    <span class="package-row-image"><i class="bi bi-coin" aria-hidden="true"></i></span>
                    <span class="package-row-body">
                        <span class="package-row-name">{{ $offer->name }}</span>
                        @if($bonus($offer) > 0)
                            <span class="package-badges"><span class="package-badge accent"><i class="bi bi-gift-fill"></i> {{ trans('theme::messages.shop.bonus', ['percent' => $bonus($offer)]) }}</span></span>
                        @endif
                    </span>
                    <span class="package-price">{{ $offer->price }} {{ currency_display() }}</span>
                    <span class="btn btn-primary btn-sm">{{ trans('theme::messages.shop.recharge') }}</span>
                </a>
            @endforeach
        </div>
    @elseif($layout === 'pricing')
        <div class="pricing">
            @foreach($offers as $offer)
                @php($featured = $best !== null && $offer->is($best))
                <div class="pricing-plan @if($featured) featured @endif">
                    @if($featured)
                        <span class="pricing-flag">{{ trans('theme::messages.shop.best_value') }}</span>
                    @endif
                    <h3>{{ $offer->name }}</h3>
                    <div class="pricing-price"><span class="package-price">{{ $offer->price }} {{ currency_display() }}</span></div>
                    <a href="{{ route('shop.offers.pay', [$offer->id, $gateway->type]) }}" class="payment-method btn w-100 {{ $featured ? 'btn-primary btn-glow' : 'btn-glass' }}">
                        {{ trans('theme::messages.shop.recharge') }}
                    </a>
                    @if($bonus($offer) > 0)
                        <div class="package-badges"><span class="package-badge accent"><i class="bi bi-gift-fill"></i> {{ trans('theme::messages.shop.bonus', ['percent' => $bonus($offer)]) }}</span></div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="row g-4 justify-content-center">
            @foreach($offers as $offer)
                <div class="col-sm-6 col-lg-3">
                    <a href="{{ route('shop.offers.pay', [$offer->id, $gateway->type]) }}" class="payment-method package-card @if($best !== null && $offer->is($best)) featured @endif">
                        <div class="package-image">
                            @if($offer->hasImage())
                                <img src="{{ $offer->imageUrl() }}" alt="{{ $offer->name }}">
                            @else
                                <i class="bi bi-coin" aria-hidden="true"></i>
                            @endif
                        </div>
                        <div class="package-body">
                            <div>
                                <h3>{{ $offer->name }}</h3>
                                @if($bonus($offer) > 0)
                                    <div class="package-badges"><span class="package-badge accent"><i class="bi bi-gift-fill"></i> {{ trans('theme::messages.shop.bonus', ['percent' => $bonus($offer)]) }}</span></div>
                                @endif
                            </div>
                            <div class="package-foot">
                                <span class="package-price">{{ $offer->price }} {{ currency_display() }}</span>
                                <span class="package-cta">{{ trans('theme::messages.shop.recharge') }} <i class="bi bi-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    <form method="POST" id="submitForm">
        @csrf
    </form>
@endsection
