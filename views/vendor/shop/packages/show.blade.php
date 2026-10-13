{{--
    Fenêtre d'achat d'un article, habillée par le thème (remplace shop::packages.show).
    Chargée par la page de catégorie dans #itemModal ; formulaires et champs identiques au plugin.
--}}
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content package-modal">
        <button type="button" class="btn-close package-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>

        <div class="row g-0">
            @if($package->hasImage())
                <div class="col-md-5">
                    <div class="package-modal-image">
                        <img src="{{ $package->imageUrl() }}" alt="{{ $package->name }}">
                    </div>
                </div>
            @endif

            <div class="col">
                <div class="package-modal-body">
                    @if($package->category)
                        <span class="eyebrow">{{ $package->category->name }}</span>
                    @endif
                    <h3 class="modal-title" id="itemModalLabel">{{ $package->name }}</h3>

                    <div class="package-modal-description">
                        {!! $package->description !!}
                    </div>

                    <div class="package-modal-footer">
                        <span class="package-price">
                            @if($package->isDiscounted())
                                <del>{{ shop_format_amount($package->getOriginalPrice()) }}</del>
                            @endif
                            {{ shop_format_amount($package->getPrice()) }}
                        </span>

                        @if($shopUser === null)
                            <a href="{{ route('shop.login') }}" class="btn btn-primary btn-glow">
                                <i class="bi bi-cart-plus"></i> {{ trans('shop::messages.buy') }}
                            </a>
                        @elseif($package->isSubscription())
                            @if($package->isUserSubscribed($shopUser))
                                <a href="{{ route('shop.profile') }}" class="btn btn-primary">
                                    {{ trans('shop::messages.actions.manage') }}
                                </a>
                            @else
                                <form action="{{ route('shop.subscriptions.select', $package) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-glow">
                                        {{ trans('shop::messages.actions.subscribe') }}
                                    </button>
                                </form>
                            @endif
                        @elseif($package->isInCart())
                            <form action="{{ route('shop.cart.remove', $package) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-glass">
                                    <i class="bi bi-cart-x"></i> {{ trans('messages.actions.remove') }}
                                </button>
                            </form>
                        @elseif($package->global_limit === 0)
                            <span class="package-modal-unavailable"><i class="bi bi-x-circle"></i> {{ trans('shop::messages.packages.unavailable') }}</span>
                        @elseif($package->getMaxQuantity() < 1)
                            <span class="package-modal-unavailable"><i class="bi bi-x-circle"></i> {{ trans('shop::messages.packages.limit') }}</span>
                        @elseif(! $package->hasBoughtRequirements())
                            <span class="package-modal-unavailable"><i class="bi bi-x-circle"></i> {{ trans('shop::messages.packages.requirements') }}</span>
                        @else
                            <form action="{{ route('shop.packages.buy', $package) }}" method="POST" class="package-modal-buy">
                                @csrf

                                @if($package->custom_price)
                                    <div class="input-group">
                                        <label class="input-group-text" for="price">{{ trans('shop::messages.fields.price') }}</label>
                                        <input type="number" step="0.01" min="{{ $package->getPrice() }}" class="form-control" name="price" id="price" value="{{ $package->getPrice() }}">
                                    </div>
                                @endif

                                @if($package->has_quantity)
                                    <div class="input-group">
                                        <label class="input-group-text" for="quantity">{{ trans('shop::messages.fields.quantity') }}</label>
                                        <input type="number" min="1" max="{{ $package->getMaxQuantity() }}" class="form-control" name="quantity" id="quantity" value="1" required>
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-primary btn-glow">
                                    <i class="bi bi-cart-plus"></i> {{ trans('shop::messages.buy') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
