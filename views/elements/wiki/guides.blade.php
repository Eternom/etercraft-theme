{{-- Wiki en sommaires : une carte par catégorie avec ses premières pages, ouvrables directement --}}
@php($shown = 5)

<div class="wiki-guides">
    @foreach($categories as $category)
        <article class="wiki-guide" data-reveal data-spotlight>
            <a href="{{ route('wiki.show', $category) }}" class="wiki-guide-head">
                <span class="wiki-icon"><i class="{{ $category->icon ?? 'bi bi-book' }}" aria-hidden="true"></i></span>
                <span>
                    <span class="wiki-guide-name">{{ $category->name }}</span>
                    <small>{{ trans_choice('theme::messages.wiki.pages', $category->pages->count()) }}</small>
                </span>
            </a>

            <ol class="wiki-guide-pages">
                @foreach($category->pages->take($shown) as $page)
                    <li><a href="{{ route('wiki.pages.show', [$category, $page]) }}">{{ $page->title }}</a></li>
                @endforeach
            </ol>

            @if($category->pages->count() > $shown)
                <a href="{{ route('wiki.show', $category) }}" class="wiki-guide-more">
                    {{ trans_choice('theme::messages.wiki.more', $category->pages->count() - $shown) }}
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            @endif
        </article>
    @endforeach
</div>
