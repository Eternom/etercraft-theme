{{--
    Rechargement : choix du montant, habillé par le thème (remplace shop::offers.select).
    Un clic envoie #submitForm (POST) vers l'offre choisie, comme dans le plugin.
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

@section('content')
    <h1>{{ trans('shop::messages.offers.amount') }}</h1>

    <div class="row g-4">
        @forelse($offers as $offer)
            <div class="col-sm-6 col-lg-3">
                <a href="{{ route('shop.offers.pay', [$offer->id, $gateway->type]) }}" class="payment-method package-card">
                    <div class="package-image">
                        @if($offer->hasImage())
                            <img src="{{ $offer->imageUrl() }}" alt="{{ $offer->name }}">
                        @else
                            <i class="bi bi-coin" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div class="package-body">
                        <h3>{{ $offer->name }}</h3>
                        <span class="package-price">{{ $offer->price }} {{ currency_display() }}</span>
                    </div>
                </a>
            </div>
        @empty
            <div class="col">
                <p class="text-body-secondary"><i class="bi bi-exclamation-circle"></i> {{ trans('shop::messages.offers.empty') }}</p>
            </div>
        @endforelse
    </div>

    <form method="POST" id="submitForm">
        @csrf
    </form>
@endsection
