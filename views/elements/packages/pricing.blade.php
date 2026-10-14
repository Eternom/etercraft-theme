{{--
    Articles d'une catégorie, disposition « grille tarifaire » : une colonne par article avec sa description complète.
    Avec trois articles, celui du milieu est mis en avant (« Recommandé »).
--}}
<div class="pricing">
    @foreach($packages as $package)
        @php($featured = $packages->count() === 3 && $loop->index === 1)
        <div class="pricing-plan @if($featured) featured @endif">
            @if($featured)
                <span class="pricing-flag">{{ trans('theme::messages.shop.recommended') }}</span>
            @endif

            <h3>{{ $package->name }}</h3>
            @if($package->short_description)
                <p class="package-short">{{ $package->short_description }}</p>
            @endif

            <div class="pricing-price">
                @include('elements.packages.price', ['package' => $package])
            </div>

            <button type="button" class="btn w-100 {{ $featured ? 'btn-primary btn-glow' : 'btn-glass' }}" data-package-url="{{ route('shop.packages.show', $package) }}">
                {{ trans('theme::messages.shop.choose') }}
            </button>

            {{-- Description de l'article (HTML saisi dans l'admin de la boutique) --}}
            <div class="pricing-features">{!! $package->description !!}</div>

            @include('elements.packages.badges', ['package' => $package])
        </div>
    @endforeach
</div>
