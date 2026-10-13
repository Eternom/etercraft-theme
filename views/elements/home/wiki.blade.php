{{-- Section Wiki (plugin Wiki) : les catégories principales visibles par le visiteur, et une recherche --}}
@if(plugins()->isEnabled('wiki') && config('theme.show_wiki', true))
    @php
        $categories = \Azuriom\Plugin\Wiki\Models\Category::scopes(['parents', 'enabled'])
            ->get()
            ->filter(fn ($category) => Gate::allows('view', $category));
    @endphp

    @unless($categories->isEmpty())
        <section id="wiki" class="section">
            <div class="container">
                <div class="section-head-split" data-reveal>
                    <div>
                        <span class="eyebrow">{{ site_name() }}</span>
                        <h2>{{ config('theme.wiki_title') ?: trans('theme::messages.wiki.title') }}</h2>
                        <p>{{ trans('theme::messages.wiki.subtitle') }}</p>
                    </div>
                    @include('elements.wiki-search')
                </div>

                @include('elements.wiki-tiles', ['categories' => $categories])
            </div>
        </section>
    @endunless
@endif
