{{-- Wiki en liste : une ligne par catégorie, avec un aperçu des titres de ses pages --}}
<div class="wiki-list">
    @foreach($categories as $category)
        <a href="{{ route('wiki.show', $category) }}" class="wiki-list-row" data-reveal>
            <span class="wiki-icon"><i class="{{ $category->icon ?? 'bi bi-book' }}" aria-hidden="true"></i></span>
            <span class="wiki-list-text">
                <span class="wiki-list-name">{{ $category->name }}</span>
                <span class="wiki-list-pages">{{ $category->pages->pluck('title')->join(' · ') }}</span>
            </span>
            <span class="wiki-list-count">{{ trans_choice('theme::messages.wiki.pages', $category->pages->count()) }}</span>
            <i class="bi bi-chevron-right wiki-tile-arrow" aria-hidden="true"></i>
        </a>
    @endforeach
</div>
