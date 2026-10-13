{{--
    Historique d'achats, habillé par le thème (remplace shop::profile.index).
    Le tableau des achats en argent du site lit bien $purchases (la vue d'origine y affiche $payments).
--}}
@extends('layouts.app')

@section('title', trans('shop::messages.profile.payments'))

@section('content')
    <h1>{{ trans('shop::messages.profile.payments') }}</h1>

    <div class="glass-card shop-table mb-4">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{ trans('shop::messages.fields.price') }}</th>
                    <th scope="col">{{ trans('messages.fields.type') }}</th>
                    <th scope="col">{{ trans('messages.fields.status') }}</th>
                    <th scope="col">{{ trans('shop::messages.fields.payment_id') }}</th>
                    <th scope="col">{{ trans('messages.fields.date') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <th scope="row">{{ $payment->id }}</th>
                        <td class="shop-table-price">{{ $payment->formatPrice() }}</td>
                        <td>{{ $payment->getTypeName() }}</td>
                        <td>
                            <span class="badge text-bg-{{ $payment->statusColor() }}">
                                {{ trans('shop::admin.payments.status.'.$payment->status) }}
                            </span>
                        </td>
                        <td class="text-body-secondary">{{ $payment->transaction_id ?? trans('messages.unknown') }}</td>
                        <td class="text-body-secondary">{{ format_date($payment->created_at, true) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-body-secondary">—</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @unless($purchases->isEmpty())
        <h2 class="shop-table-title">{{ trans('shop::messages.profile.purchases') }}</h2>

        <div class="glass-card shop-table mb-4">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ trans('shop::messages.fields.price') }}</th>
                        <th scope="col">{{ trans('messages.fields.date') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($purchases as $purchase)
                        <tr>
                            <th scope="row">{{ $purchase->id }}</th>
                            <td class="shop-table-price">{{ $purchase->formatPrice() }}</td>
                            <td class="text-body-secondary">{{ format_date($purchase->created_at, true) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endunless

    @unless($subscriptions->isEmpty())
        <h2 class="shop-table-title">{{ trans('shop::messages.profile.subscriptions') }}</h2>

        <div class="glass-card shop-table mb-4">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ trans('shop::messages.fields.price') }}</th>
                        @if(! use_site_money())
                            <th scope="col">{{ trans('messages.fields.type') }}</th>
                        @endif
                        <th scope="col">{{ trans('shop::messages.fields.package') }}</th>
                        <th scope="col">{{ trans('messages.fields.status') }}</th>
                        <th scope="col">{{ trans('shop::messages.fields.subscription_id') }}</th>
                        <th scope="col">{{ trans('messages.fields.date') }}</th>
                        <th scope="col">{{ trans('shop::messages.fields.renewal_date') }}</th>
                        <th scope="col">{{ trans('messages.fields.action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subscriptions as $subscription)
                        <tr>
                            <th scope="row">{{ $subscription->id }}</th>
                            <td class="shop-table-price">{{ $subscription->formatPrice() }}</td>
                            @if(! use_site_money())
                                <td>{{ $subscription->getTypeName() }}</td>
                            @endif
                            <td>{{ $subscription->package?->name ?? trans('messages.unknown') }}</td>
                            <td>
                                <span class="badge text-bg-{{ $subscription->statusColor() }}">
                                    {{ trans('shop::admin.subscriptions.status.'.$subscription->status) }}
                                </span>
                            </td>
                            <td class="text-body-secondary">{{ $subscription->subscription_id ?? trans('messages.unknown') }}</td>
                            <td class="text-body-secondary">{{ format_date($subscription->created_at) }}</td>
                            <td class="text-body-secondary">{{ $subscription->ends_at !== null ? format_date($subscription->ends_at) : '—' }}</td>
                            <td>
                                @if($subscription->isActive() && ! $subscription->isCanceled())
                                    <form action="{{ route('shop.subscriptions.destroy', $subscription) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-glass btn-sm text-danger">
                                            <i class="bi bi-x-circle"></i> {{ trans('messages.actions.cancel') }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endunless
@endsection
