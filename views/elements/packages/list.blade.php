{{-- Articles d'une catégorie, disposition « liste » : une ligne par article, pratique quand il y en a beaucoup --}}
<div class="package-list">
    @foreach($packages as $package)
        <a href="#" class="package-row" data-package-url="{{ route('shop.packages.show', $package) }}">
            <span class="package-row-image">
                @if($package->hasImage())
                    <img src="{{ $package->imageUrl() }}" alt="" loading="lazy">
                @else
                    <i class="bi bi-bag" aria-hidden="true"></i>
                @endif
            </span>
            <span class="package-row-body">
                <span class="package-row-name">{{ $package->name }}</span>
                @if($package->short_description)
                    <span class="package-short">{{ $package->short_description }}</span>
                @endif
                @include('elements.packages.badges', ['package' => $package])
            </span>
            @include('elements.packages.price', ['package' => $package])
            <span class="btn btn-primary btn-sm">{{ trans('theme::messages.shop.see') }}</span>
        </a>
    @endforeach
</div>
