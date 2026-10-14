{{--
    Page du Wiki, habillée par le thème (remplace wiki::pages.show). Comme l'original, toutes les pages de la catégorie
    sont rendues en onglets : passer de l'une à l'autre est instantané et met à jour l'adresse et le titre.
    En plus : fil d'Ariane, sommaire numéroté qui reste à l'écran, accès aux autres catégories, page précédente/suivante.
--}}
@extends('layouts.app')

@section('title', $page->title)

@php
    $category = $page->category;
    $pages = $category->pages->values();
    $back = $category->parent !== null ? route('wiki.show', $category->parent) : route('wiki.index');

    $categories = \Azuriom\Plugin\Wiki\Models\Category::scopes(['parents', 'enabled'])
        ->get()
        ->filter(fn ($other) => Gate::allows('view', $other));
@endphp

@section('content')
    <nav class="wiki-breadcrumb" aria-label="{{ trans('wiki::messages.title') }}">
        <a href="{{ route('wiki.index') }}">{{ trans('wiki::messages.title') }}</a>
        @if($category->parent !== null)
            <i class="bi bi-chevron-right" aria-hidden="true"></i>
            <a href="{{ route('wiki.show', $category->parent) }}">{{ $category->parent->name }}</a>
        @endif
        <i class="bi bi-chevron-right" aria-hidden="true"></i>
        <span>{{ $category->name }}</span>
    </nav>

    @include('wiki::partials._header', ['title' => $category->name])

    <div class="row g-4" id="wiki">
        <aside class="col-lg-3">
            <div class="wiki-aside">
                @unless($category->categories->isEmpty())
                    <div class="wiki-aside-block">
                        @foreach($category->categories as $subCategory)
                            @can('view', $subCategory)
                                <a href="{{ route('wiki.show', $subCategory) }}" class="wiki-toc-link">
                                    <i class="{{ $subCategory->icon ?? 'bi bi-book' }}" aria-hidden="true"></i> {{ $subCategory->name }}
                                </a>
                            @endcan
                        @endforeach
                    </div>
                @endunless

                <div class="wiki-aside-block">
                    <span class="wiki-aside-label">{{ trans('theme::messages.wiki.in_category') }}</span>
                    <div class="wiki-toc" role="tablist">
                        @foreach($pages as $catPage)
                            <a href="{{ route('wiki.pages.show', [$category, $catPage]) }}" id="wiki-tab-{{ $catPage->id }}"
                               class="wiki-toc-link @if($page->is($catPage)) active @endif"
                               title="{{ $catPage->title }}" onclick="selectWikiPage(this)"
                               data-bs-toggle="tab" data-bs-target="#page-{{ $catPage->id }}" role="tab"
                               aria-controls="page-{{ $catPage->id }}" aria-selected="{{ $page->is($catPage) ? 'true' : 'false' }}">
                                <span class="wiki-toc-number">{{ $loop->iteration }}</span>
                                {{ $catPage->title }}
                            </a>
                        @endforeach
                    </div>
                </div>

                @if($categories->count() > 1)
                    <div class="wiki-aside-block">
                        <span class="wiki-aside-label">{{ trans('theme::messages.wiki.categories') }}</span>
                        <div class="wiki-jump">
                            @foreach($categories as $other)
                                <a href="{{ route('wiki.show', $other) }}" title="{{ $other->name }}" aria-label="{{ $other->name }}"
                                   @class(['active' => $other->is($category) || $other->is($category->parent)])>
                                    <i class="{{ $other->icon ?? 'bi bi-book' }}" aria-hidden="true"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <a href="{{ $back }}" class="wiki-back">
                    <i class="bi bi-arrow-left" aria-hidden="true"></i> {{ trans('wiki::messages.back') }}
                </a>
            </div>
        </aside>

        <div class="col-lg-9 tab-content">
            @foreach($pages as $catPage)
                @php([$previous, $next] = [$pages->get($loop->index - 1), $pages->get($loop->index + 1)])

                <div class="tab-pane fade @if($page->is($catPage)) show active @endif" id="page-{{ $catPage->id }}" role="tabpanel" aria-labelledby="wiki-tab-{{ $catPage->id }}">
                    <article class="card wiki-article">
                        <div class="card-body">
                            <header class="wiki-article-head">
                                <span class="eyebrow">{{ $category->name }}</span>
                                <h2 class="wiki-article-title">{{ $catPage->title }}</h2>
                            </header>

                            <div class="rich-content">
                                {!! $catPage->content !!}
                            </div>
                        </div>

                        @if($previous !== null || $next !== null)
                            <footer class="wiki-pager">
                                @if($previous !== null)
                                    <a href="{{ route('wiki.pages.show', [$category, $previous]) }}" data-wiki-page="{{ $previous->id }}">
                                        <small><i class="bi bi-arrow-left" aria-hidden="true"></i> {{ trans('theme::messages.wiki.previous') }}</small>
                                        <span>{{ $previous->title }}</span>
                                    </a>
                                @endif
                                @if($next !== null)
                                    <a href="{{ route('wiki.pages.show', [$category, $next]) }}" data-wiki-page="{{ $next->id }}" class="wiki-pager-next">
                                        <small>{{ trans('theme::messages.wiki.next') }} <i class="bi bi-arrow-right" aria-hidden="true"></i></small>
                                        <span>{{ $next->title }}</span>
                                    </a>
                                @endif
                            </footer>
                        @endif
                    </article>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentTitle = @json($page->title);

        function selectWikiPage(element, replaceState = false) {
            bootstrap.Tab.getOrCreateInstance(element).show();

            if (replaceState) {
                window.history.replaceState({}, '', element.href);
            } else {
                window.history.pushState({}, '', element.href);
            }

            document.title = document.title.replace(currentTitle, element.title);
            currentTitle = element.title;
        }

        // Précédent / suivant : même changement d'onglet que le sommaire, puis retour en haut de la page
        document.addEventListener('click', (event) => {
            const link = event.target.closest('[data-wiki-page]');

            if (link) {
                event.preventDefault();
                selectWikiPage(document.getElementById('wiki-tab-' + link.dataset.wikiPage));
                document.getElementById('wiki').scrollIntoView({ behavior: 'smooth' });
            }
        });

        window.onpopstate = function (e) {
            const target = document.querySelector('.wiki-toc [href="' + e.target.location.href + '"]');

            if (target) {
                selectWikiPage(target, true);
            }
        };
    </script>
@endpush
