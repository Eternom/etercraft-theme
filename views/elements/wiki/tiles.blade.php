{{-- Wiki en tuiles : icône, nom et nombre de pages (pages_count chargé par l'appelant) --}}
<div class="row g-3">
    @foreach($categories as $category)
        <div class="col-sm-6 col-lg-4 col-xl-3" data-reveal>
            <a href="{{ route('wiki.show', $category) }}" class="wiki-tile" data-spotlight>
                <span class="wiki-icon"><i class="{{ $category->icon ?? 'bi bi-book' }}" aria-hidden="true"></i></span>
                <span class="wiki-tile-text">
                    <span class="wiki-tile-name">{{ $category->name }}</span>
                    @isset($category->pages_count)
                        <small>{{ trans_choice('theme::messages.wiki.pages', $category->pages_count) }}</small>
                    @endisset
                </span>
                <i class="bi bi-arrow-right wiki-tile-arrow" aria-hidden="true"></i>
            </a>
        </div>
    @endforeach
</div>
