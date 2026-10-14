{{-- Prix d'un article ($package) : ancien prix barré s'il est en promotion, période pour un abonnement --}}
<span class="package-price">
    @if($package->isDiscounted())
        <del>{{ shop_format_amount($package->getOriginalPrice()) }}</del>
    @endif
    {{ shop_format_amount($package->getPrice()) }}
    @if($package->isSubscription())
        <small class="package-period">/ @include('elements.packages.period', ['package' => $package])</small>
    @endif
</span>
