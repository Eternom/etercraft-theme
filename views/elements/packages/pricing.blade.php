{{--
    Articles d'une catégorie, disposition « grille tarifaire » : une colonne par article avec sa description complète.
    Avec trois articles, celui du milieu est mis en avant (« Recommandé »).
    Chaque colonne a toujours les mêmes 6 blocs, même vides : la sous-grille CSS aligne ainsi titres, prix, boutons
    et listes d'une colonne à l'autre.
--}}
<div class="pricing">
    @foreach($packages as $package)
        @php($featured = $packages->count() === 3 && $loop->index === 1)
        <div class="pricing-plan @if($featured) featured @endif">
            @if($featured)
                <span class="pricing-flag">{{ trans('theme::messages.shop.recommended') }}</span>
            @endif

            <h3>{{ $package->name }}</h3>
            <p class="package-short">{{ $package->short_description }}</p>

            <div class="pricing-price">
                @include('elements.packages.price', ['package' => $package])
            </div>

            <button type="button" class="btn w-100 {{ $featured ? 'btn-primary btn-glow' : 'btn-glass' }}" data-package-url="{{ route('shop.packages.show', $package) }}">
                {{ trans('theme::messages.shop.choose') }}
            </button>

            {{-- Description de l'article (HTML saisi dans l'admin de la boutique) --}}
            <div class="pricing-features">{!! $package->description !!}</div>

            <div class="pricing-badges">
                @include('elements.packages.badges', ['package' => $package])
            </div>
        </div>
    @endforeach
</div>
