{{-- Étiquettes d'un article ($package) : promotion, type d'achat, limites, prérequis, état pour le joueur --}}
@php
    $badges = []; // [icône, texte, ton : '' | accent | success]
    if ($package->isDiscounted() && $package->getOriginalPrice() > 0) {
        $badges[] = ['tag-fill', '−'.round(100 - $package->getPrice() / $package->getOriginalPrice() * 100).' %', 'accent'];
    }
    if ($package->isSubscription()) {
        $badges[] = ['arrow-repeat', trans('theme::messages.shop.subscription'), ''];
    } elseif ($package->billing_type === 'expiring') {
        $badges[] = ['hourglass-split', trim(view('elements.packages.period', ['package' => $package])->render()), ''];
    }
    if ($package->has_quantity) {
        $badges[] = ['stack', trans('theme::messages.shop.quantity'), ''];
    }
    if ($package->user_limit) {
        $badges[] = ['person-check', trans_choice('theme::messages.shop.limit', $package->user_limit), ''];
    }
    if ($package->required_packages?->isNotEmpty()) {
        $required = \Azuriom\Plugin\Shop\Models\Package::whereIn('id', $package->required_packages)->pluck('name')->join(', ');
        $badges[] = ['lock', trans('theme::messages.shop.requires', ['packages' => $required]), ''];
    }
    // État pour le joueur connecté (un visiteur n'a ni abonnement ni panier)
    if (shop_user() !== null && $package->isUserSubscribed(shop_user())) {
        $badges[] = ['check-circle-fill', trans('theme::messages.shop.subscribed'), 'success'];
    } elseif ($package->isInCart()) {
        $badges[] = ['cart-check-fill', trans('theme::messages.shop.in_cart'), 'success'];
    }
@endphp

@if($badges)
    <div class="package-badges">
        @foreach($badges as [$icon, $label, $tone])
            <span class="package-badge {{ $tone }}"><i class="bi bi-{{ $icon }}" aria-hidden="true"></i> {{ $label }}</span>
        @endforeach
    </div>
@endif
