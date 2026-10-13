{{--
    Barre latérale de la boutique, habillée par le thème (remplace shop::categories._sidebar).
    $shopUser et $userHasPayments sont fournis par le plugin (ShopViewComposer) à toutes ses vues.
--}}
<nav class="shop-nav mb-4">
    @if($displayHome)
        <a href="{{ route('shop.home') }}" class="@if($category === null) active @endif">
            <i class="bi bi-house" aria-hidden="true"></i> {{ trans('messages.home') }}
        </a>
    @endif

    @foreach($categories as $subCategory)
        <a href="{{ route('shop.categories.show', $subCategory) }}" class="@if($subCategory->is($category)) active @endif">
            <i class="{{ $subCategory->icon ?? 'bi bi-bag' }}" aria-hidden="true"></i> {{ $subCategory->name }}
        </a>

        @foreach($subCategory->categories as $cat)
            <a href="{{ route('shop.categories.show', $cat) }}" class="shop-nav-child @if($cat->is($category)) active @endif">
                @if($cat->icon)
                    <i class="{{ $cat->icon }}" aria-hidden="true"></i>
                @endif
                {{ $cat->name }}
            </a>
        @endforeach
    @endforeach
</nav>

<div class="glass-card shop-account mb-4">
    @if($shopUser !== null)
        <div class="d-flex align-items-center gap-3 mb-3">
            <img src="{{ $shopUser->getAvatar(64) }}" alt="" class="shop-account-avatar" width="48" height="48">
            <div class="min-w-0">
                <p class="shop-account-name">{{ $shopUser->name }}</p>
                @if(use_site_money())
                    <span class="text-body-secondary small">
                        {{ trans('shop::messages.profile.money', ['balance' => format_money($shopUser->money)]) }}
                    </span>
                @endif
            </div>
        </div>

        <div class="d-grid gap-2">
            <a href="{{ route('shop.cart.index') }}" class="btn btn-primary">
                <i class="bi bi-cart"></i> {{ trans('shop::messages.cart.title') }}
            </a>

            @if(use_site_money())
                <a href="{{ route('shop.offers.select') }}" class="btn btn-glass">
                    <i class="bi bi-credit-card"></i> {{ trans('shop::messages.cart.credit') }}
                </a>
            @endif

            @if($userHasPayments)
                <a href="{{ route('shop.profile') }}" class="btn btn-glass">
                    <i class="bi bi-cash-coin"></i> {{ trans('shop::messages.profile.payments') }}
                </a>
            @endif

            @guest
                <form action="{{ route('shop.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-glass w-100">
                        <i class="bi bi-box-arrow-right"></i> {{ trans('auth.logout') }}
                    </button>
                </form>
            @endguest
        </div>
    @else
        <a href="{{ route('shop.login') }}" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right"></i> {{ trans('auth.login') }}
        </a>
    @endif
</div>

@if($goal >= 0)
    <div class="glass-card shop-side-card mb-4">
        <p class="shop-side-title"><i class="bi bi-graph-up"></i> {{ trans('shop::messages.goal.title') }}</p>
        <div class="progress vote-goal-bar mb-2">
            <div class="progress-bar" role="progressbar" aria-valuenow="{{ $goal }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ min($goal, 100) }}%"></div>
        </div>
        <span class="text-body-secondary small">{{ trans_choice('shop::messages.goal.progress', $goal) }}</span>
    </div>
@endif

@if($topCustomer !== null)
    <div class="glass-card shop-side-card mb-4">
        <p class="shop-side-title"><i class="bi bi-star"></i> {{ trans('shop::messages.top.title') }}</p>
        <div class="d-flex align-items-center gap-3">
            <img src="{{ $topCustomer->user->getAvatar(64) }}" alt="" class="shop-account-avatar" width="48" height="48">
            <div>
                <p class="shop-account-name">{{ $topCustomer->user->name }}</p>
                @if($displaySidebarAmount)
                    <span class="text-body-secondary small">{{ $topCustomer->formatPrice() }}</span>
                @endif
            </div>
        </div>
    </div>
@endif

@if($recentPayments !== null)
    <div class="glass-card shop-side-card mb-4">
        <p class="shop-side-title"><i class="bi bi-list-check"></i> {{ trans('shop::messages.recent.title') }}</p>
        @forelse($recentPayments as $payment)
            <div class="shop-recent">
                <img src="{{ $payment->user->getAvatar(48) }}" alt="" width="32" height="32">
                <div>
                    <span>{{ $payment->user->name }}</span>
                    <small class="text-body-secondary d-block">
                        @if($displaySidebarAmount)
                            {{ $payment->formatPrice() }} ·
                        @endif
                        {{ format_date($payment->created_at) }}
                    </small>
                </div>
            </div>
        @empty
            <span class="text-body-secondary small">{{ trans('shop::messages.recent.empty') }}</span>
        @endforelse
    </div>
@endif
