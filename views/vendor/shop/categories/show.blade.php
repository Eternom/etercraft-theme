{{--
    Catégorie de la boutique, habillée par le thème (remplace shop::categories.show), en cartes, liste ou grille tarifaire.
    Un clic sur un article charge sa fenêtre d'achat depuis le plugin ([data-package-url] → #itemModal).
--}}
@extends('layouts.app')

@section('title', $category->name)

@push('footer-scripts')
    <script>
        document.querySelectorAll('[data-package-url]').forEach(function (el) {
            el.addEventListener('click', function (ev) {
                ev.preventDefault();

                axios.get(el.dataset['packageUrl']).then(function (response) {
                    const itemModal = document.getElementById('itemModal');
                    itemModal.innerHTML = response.data;
                    new bootstrap.Modal(itemModal).show();
                }).catch(function (error) {
                    createAlert('danger', error, true);
                });
            });
        });
    </script>
@endpush

@section('content')
    <h1>{{ $category->name }}</h1>

    <div class="row g-4" id="shop">
        <div class="col-lg-3">
            @include('shop::categories._sidebar')
        </div>

        <div class="col-lg-9">
            @if($category->description)
                <div class="glass-card p-4 mb-4">
                    {!! $category->description !!}
                </div>
            @endif

            @if($category->packages->isEmpty())
                <p class="text-body-secondary">
                    <i class="bi bi-exclamation-circle"></i> {{ trans('shop::messages.categories.empty') }}
                </p>
            @else
                {{-- Disposition : grille tarifaire pour les catégories choisies, sinon celle de la config du thème --}}
                @php
                    $layout = in_array($category->id, config('theme.shop_pricing_categories') ?? [])
                        ? 'pricing'
                        : (in_array(config('theme.shop_layout'), ['list', 'pricing'], true) ? config('theme.shop_layout') : 'cards');
                @endphp
                @include('elements.packages.'.$layout, ['packages' => $category->packages])
            @endif
        </div>
    </div>

    <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true"></div>
@endsection
