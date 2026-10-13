{{--
    Panier de la boutique, habillé par le thème (remplace shop::cart.index).
    Articles à gauche, récapitulatif à droite. Formulaires, champs et routes identiques au plugin.
--}}
@extends('layouts.app')

@section('title', trans('shop::messages.cart.title'))

@section('content')
    <h1>{{ trans('shop::messages.cart.title') }}</h1>

    @if($cart->isEmpty())
        <div class="glass-card cart-empty">
            <i class="bi bi-cart" aria-hidden="true"></i>
            <p>{{ trans('shop::messages.cart.empty') }}</p>
            <a href="{{ route('shop.home') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> {{ trans('shop::messages.cart.back') }}
            </a>
        </div>
    @else
        <div class="row g-4 align-items-start">
            <div class="col-lg-8">
                <form action="{{ route('shop.cart.update') }}" method="POST" id="cartUpdateForm">
                    @csrf

                    <div class="cart-items">
                        @foreach($cart->content() as $cartItem)
                            @php($buyable = $cartItem->buyable())
                            <div class="glass-card cart-item">
                                <div class="cart-item-image">
                                    @if(method_exists($buyable, 'hasImage') && $buyable->hasImage())
                                        <img src="{{ $buyable->imageUrl() }}" alt="">
                                    @else
                                        <i class="bi bi-bag" aria-hidden="true"></i>
                                    @endif
                                </div>

                                <div class="cart-item-body">
                                    <h2>{{ $cartItem->name() }}</h2>
                                    <span class="text-body-secondary">{{ shop_format_amount($cartItem->price()) }}</span>
                                </div>

                                <input type="number" min="0" max="{{ $cartItem->maxQuantity() }}" class="form-control cart-item-quantity"
                                       name="quantities[{{ $cartItem->itemId }}]" value="{{ $cartItem->quantity }}"
                                       aria-label="{{ trans('shop::messages.fields.quantity') }}"
                                       required @if(! $cartItem->hasQuantity()) readonly @endif>

                                <span class="cart-item-total">{{ shop_format_amount($cartItem->total()) }}</span>

                                <a href="{{ route('shop.cart.remove', $cartItem->id) }}" class="cart-item-remove" title="{{ trans('messages.actions.delete') }}">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </form>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <button type="submit" form="cartUpdateForm" class="btn btn-glass btn-sm">
                        <i class="bi bi-arrow-repeat"></i> {{ trans('messages.actions.update') }}
                    </button>

                    <form method="POST" action="{{ route('shop.cart.clear') }}">
                        @csrf
                        <button type="submit" class="btn btn-glass btn-sm text-danger">
                            <i class="bi bi-trash"></i> {{ trans('shop::messages.cart.clear') }}
                        </button>
                    </form>

                    <a href="{{ route('shop.home') }}" class="btn btn-glass btn-sm ms-auto">
                        <i class="bi bi-arrow-left"></i> {{ trans('shop::messages.cart.back') }}
                    </a>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card cart-summary">
                    {{-- Codes promo --}}
                    <form action="{{ route('shop.cart.coupons.add') }}" method="POST">
                        @csrf
                        <label class="form-label" for="coupon">{{ trans('shop::messages.coupons.add') }}</label>
                        <div class="input-group @error('coupon') has-validation @enderror">
                            <input type="text" class="form-control @error('coupon') is-invalid @enderror" id="coupon" name="coupon"
                                   value="{{ old('coupon') }}" placeholder="{{ trans('shop::messages.fields.code') }}" required>
                            <button type="submit" class="btn btn-glass" title="{{ trans('messages.actions.add') }}">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                            @error('coupon')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </form>

                    @foreach($cart->coupons() as $coupon)
                        <form action="{{ route('shop.cart.coupons.remove', $coupon) }}" method="POST" class="cart-code">
                            @csrf
                            <span><i class="bi bi-tag"></i> {{ $coupon->code }}</span>
                            <span>−{{ $coupon->is_fixed ? shop_format_amount($coupon->discount) : $coupon->discount.' %' }}</span>
                            <button type="submit" title="{{ trans('messages.actions.delete') }}"><i class="bi bi-x-lg"></i></button>
                        </form>
                    @endforeach

                    {{-- Cartes cadeaux --}}
                    <form action="{{ route('shop.cart.giftcards.add') }}" method="POST" class="mt-3">
                        @csrf
                        <label class="form-label" for="giftcard">{{ trans('shop::messages.giftcards.add') }}</label>
                        <div class="input-group @error('giftcard') has-validation @enderror">
                            <input type="text" class="form-control @error('giftcard') is-invalid @enderror" id="giftcard" name="giftcard"
                                   value="{{ old('giftcard') }}" placeholder="{{ trans('shop::messages.fields.code') }}" required>
                            <button type="submit" class="btn btn-glass" title="{{ trans('messages.actions.add') }}">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                            @error('giftcard')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </form>

                    @foreach($cart->giftcards() as $giftcard)
                        <form action="{{ route('shop.cart.giftcards.remove', $giftcard) }}" method="POST" class="cart-code">
                            @csrf
                            <span><i class="bi bi-gift"></i> {{ $giftcard->code }}</span>
                            <span>{{ shop_format_amount($giftcard->balance) }}</span>
                            <button type="submit" title="{{ trans('messages.actions.delete') }}"><i class="bi bi-x-lg"></i></button>
                        </form>
                    @endforeach

                    {{-- Totaux --}}
                    <div class="cart-totals">
                        <p>{{ trans('shop::messages.cart.total', ['total' => shop_format_amount($cart->total())]) }}</p>
                        @unless($cart->giftcards()->isEmpty())
                            <p class="cart-payable">{{ trans('shop::messages.cart.payable_total', ['total' => shop_format_amount($cart->payableTotal())]) }}</p>
                        @endunless
                    </div>

                    {{-- Paiement : en argent du site (confirmation) ou via un moyen de paiement --}}
                    @if(use_site_money())
                        <button type="button" class="btn btn-primary btn-lg btn-glow w-100" data-bs-toggle="modal" data-bs-target="#confirmBuyModal">
                            <i class="bi bi-cart-check"></i> {{ trans('shop::messages.buy') }}
                        </button>
                    @else
                        <form action="{{ route('shop.payments.payment') }}" class="d-grid gap-3">
                            @include('shop::cart._terms', ['terms' => $terms])

                            @if($emailRequired)
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                       value="{{ old('email') }}" placeholder="{{ trans('auth.email') }}" required>
                            @endif

                            <button type="submit" class="btn btn-primary btn-lg btn-glow">
                                <i class="bi bi-cart-check"></i> {{ trans('shop::messages.cart.checkout') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if(use_site_money() && ! $cart->isEmpty())
        <div class="modal fade" id="confirmBuyModal" tabindex="-1" role="dialog" aria-labelledby="confirmBuyLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="confirmBuyLabel">{{ trans('shop::messages.cart.confirm.title') }}</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{ trans('shop::messages.cart.confirm.price', ['price' => shop_format_amount($cart->payableTotal())]) }}
                    </div>

                    <form class="modal-footer" method="POST" action="{{ route('shop.cart.payment') }}">
                        @csrf

                        @include('shop::cart._terms', ['terms' => $terms])

                        <div class="ms-auto">
                            <button class="btn btn-glass" type="button" data-bs-dismiss="modal">{{ trans('messages.actions.cancel') }}</button>
                            <button class="btn btn-primary" type="submit">{{ trans('shop::messages.cart.pay') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection
