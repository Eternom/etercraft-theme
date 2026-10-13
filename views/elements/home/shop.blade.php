{{-- Section Boutique (plugin Shop) : quelques articles actifs tirés au hasard, chacun mène à sa catégorie --}}
@if(plugins()->isEnabled('shop') && config('theme.show_shop', true))
    @php
        $packages = \Azuriom\Plugin\Shop\Models\Package::scopes('enabled')
            ->whereHas('category', fn ($query) => $query->where('is_enabled', true))
            ->with(['category', 'discounts'])
            ->inRandomOrder()
            ->take((int) config('theme.shop_count', 4))
            ->get();
    @endphp

    @unless($packages->isEmpty())
        <section id="shop" class="section">
            <div class="container">
                <div class="section-head-split" data-reveal>
                    <div>
                        <span class="eyebrow">{{ site_name() }}</span>
                        <h2>{{ config('theme.shop_title') ?: trans('theme::messages.shop.title') }}</h2>
                        <p>{{ trans('theme::messages.shop.subtitle') }}</p>
                    </div>
                    <a href="{{ route('shop.home') }}" class="btn btn-glass">
                        {{ trans('theme::messages.shop.see_all') }} <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="row g-4">
                    @foreach($packages as $package)
                        <div class="col-sm-6 col-lg-3" data-reveal>
                            @include('elements.package-card', [
                                'package' => $package,
                                'link' => route('shop.categories.show', $package->category),
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endunless
@endif
