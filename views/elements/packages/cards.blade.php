{{-- Articles d'une catégorie, disposition « cartes » --}}
<div class="row g-4">
    @foreach($packages as $package)
        <div class="col-sm-6 col-xl-4">
            @include('elements.package-card', ['package' => $package])
        </div>
    @endforeach
</div>
