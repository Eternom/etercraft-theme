{{-- Tuile d'un moyen de paiement ($gateway, $href). La classe .payment-method est utilisée par les scripts des pages. --}}
<div class="col-6 col-md-4 col-lg-3">
    <a href="{{ $href }}" class="payment-method shop-gateway" title="{{ $gateway->name }}">
        <img src="{{ $gateway->paymentMethod()->image() }}" alt="{{ $gateway->name }}">
    </a>
</div>
