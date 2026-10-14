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
        <div>
            <h3>{{ $package->name }}</h3>
            @if($package->short_description)
                <p class="package-short">{{ $package->short_description }}</p>
            @endif
            @include('elements.packages.badges', ['package' => $package])
        </div>
        <div class="package-foot">
            @include('elements.packages.price', ['package' => $package])
            <span class="package-cta">{{ trans('theme::messages.shop.see') }} <i class="bi bi-arrow-right"></i></span>
        </div>
    </div>
</a>
