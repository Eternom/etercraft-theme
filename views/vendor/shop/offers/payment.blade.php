{{-- Rechargement : choix du moyen de paiement, habillé par le thème (remplace shop::offers.payment) --}}
@extends('layouts.app')

@section('title', trans('shop::messages.offers.gateway'))

@section('content')
    <h1>{{ trans('shop::messages.offers.gateway') }}</h1>

    <div class="row g-3">
        @forelse($gateways as $gateway)
            @include('elements.shop-gateway', ['gateway' => $gateway, 'href' => route('shop.offers.buy', $gateway)])
        @empty
            <div class="col">
                <p class="text-body-secondary"><i class="bi bi-exclamation-circle"></i> {{ trans('shop::messages.payment.empty') }}</p>
            </div>
        @endforelse
    </div>
@endsection
