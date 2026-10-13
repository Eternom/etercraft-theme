{{-- Carte d'un article de la boutique. Sans $link, elle ouvre la fenêtre d'achat du plugin (data-package-url). --}}
<a href="{{ $link ?? '#' }}" class="package-card" @unless(isset($link)) data-package-url="{{ route('shop.packages.show', $package) }}" @endunless>
    <div class="package-image">
        @if($package->hasImage())
            <img src="{{ $package->imageUrl() }}" alt="{{ $package->name }}" loading="lazy">
        @else
            <i class="bi bi-bag" aria-hidden="true"></i>
        @endif
    </div>
    <div class="package-body">
        <h3>{{ $package->name }}</h3>
        <span class="package-price">
            @if($package->isDiscounted())
                <del>{{ shop_format_amount($package->getOriginalPrice()) }}</del>
            @endif
            {{ shop_format_amount($package->getPrice()) }}
        </span>
    </div>
</a>
