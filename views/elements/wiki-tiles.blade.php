<div class="row g-3">
    @foreach($categories as $category)
        <div class="col-6 col-md-4 col-lg-3" data-reveal>
            <a href="{{ route('wiki.show', $category) }}" class="wiki-tile" data-spotlight>
                <i class="{{ $category->icon ?? 'bi bi-book' }}" aria-hidden="true"></i>
                <span>{{ $category->name }}</span>
                <i class="bi bi-arrow-right wiki-tile-arrow" aria-hidden="true"></i>
            </a>
        </div>
    @endforeach
</div>
