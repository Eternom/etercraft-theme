{{--
    Choix du moyen de paiement, habillé par le thème (remplace shop::payments.pay).
    Un clic envoie #submitForm (POST) vers l'adresse du moyen choisi, comme dans le plugin.
--}}
@extends('layouts.app')

@section('title', trans('shop::messages.payment.title'))

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
    <h1>{{ trans('shop::messages.payment.title') }}</h1>

    <div class="row g-3">
        @forelse($gateways as $gateway)
            @include('elements.shop-gateway', [
                'gateway' => $gateway,
                'href' => isset($route) ? $route($gateway->type) : route('shop.payments.pay', $gateway->type),
            ])
        @empty
            <div class="col">
                <p class="text-body-secondary"><i class="bi bi-exclamation-circle"></i> {{ trans('shop::messages.payment.empty') }}</p>
            </div>
        @endforelse
    </div>

    <form method="POST" id="submitForm">
        @csrf
    </form>
@endsection
