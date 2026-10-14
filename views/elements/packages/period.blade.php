{{-- Durée d'un abonnement ou d'un accès temporaire ($package), ex : « mois », « 30 jours ». Vide pour un achat simple. --}}
@php
    $count = $package->subscriptionPeriodCount();
    $unit = $package->subscriptionPeriodUnit();
@endphp
@if($count > 0)
{{ ($count > 1 ? $count.' ' : '').trans_choice('theme::messages.shop.periods.'.$unit, $count) }}
@endif
