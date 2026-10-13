{{--
    Catégorie de la boutique, habillée par le thème (remplace shop::categories.show).
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

            <div class="row g-4">
                @forelse($category->packages as $package)
                    <div class="col-sm-6 col-xl-4">
                        @include('elements.package-card', ['package' => $package])
                    </div>
                @empty
                    <div class="col">
                        <p class="text-body-secondary">
                            <i class="bi bi-exclamation-circle"></i> {{ trans('shop::messages.categories.empty') }}
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true"></div>
@endsection
