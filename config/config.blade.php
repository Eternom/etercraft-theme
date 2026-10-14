@extends('admin.layouts.admin')

@section('title', 'ThxClaude')

@php
    // Les listes sont construites côté navigateur à partir de ces valeurs (voir le script en bas)
    $features = array_values(old('features', config('theme.features') ?: trans('theme::messages.features.items')));
    $joinSteps = array_values(old('join_steps', config('theme.join_steps') ?: trans('theme::messages.join.steps')));
    $footerLinks = array_values(old('footer_links', config('theme.footer_links') ?? []));
    $images = \Azuriom\Models\Image::all();

    // Plugins pris en charge : chacun a sa rubrique et sa section d'accueil, seulement s'il est actif
    $plugins = array_values(array_filter(['vote', 'shop', 'wiki', 'forum'], fn (string $plugin) => plugins()->isEnabled($plugin)));
    $pluginIcons = ['vote' => 'hand-thumbs-up', 'shop' => 'bag', 'wiki' => 'book', 'forum' => 'chat-square-text'];
    $pluginCounts = ['shop' => [4, 8], 'forum' => [5, 10]]; // [valeur par défaut, maximum]
    $tabs = ['home' => 'house', 'features' => 'grid', 'join' => 'signpost-2']
        + array_intersect_key($pluginIcons, array_flip($plugins))
        + ['footer' => 'layout-text-window-reverse'];

    // Même règle que l'accueil : une section absente de la config (ajoutée plus tard) va à la fin
    $sections = ['features', 'join', 'news', ...$plugins];
    $sectionOrder = array_values(array_unique(array_merge(array_intersect(old('sections', config('theme.sections') ?? []), $sections), $sections)));
@endphp

@section('content')
    {{-- Ce qu'Azuriom règle déjà n'est pas recopié ici : on renvoie vers ses pages --}}
    <div class="alert alert-info">
        <p class="mb-2">{{ trans('theme::messages.config.azuriom_info') }}</p>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-sm btn-info me-2">
            <i class="bi bi-gear"></i> {{ trans('theme::messages.config.azuriom_settings') }}
        </a>
        @if(Route::has('admin.social-links.index'))
            <a href="{{ route('admin.social-links.index') }}" class="btn btn-sm btn-info">
                <i class="bi bi-share"></i> {{ trans('theme::messages.config.azuriom_social') }}
            </a>
        @endif
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-3">
            <nav class="nav nav-pills flex-lg-column gap-1 sticky-lg-top" id="configTabs">
                @foreach($tabs as $tab => $icon)
                    <a class="nav-link" href="#tab-{{ $tab }}" data-config-tab-link>
                        <i class="bi bi-{{ $icon }} me-1"></i> {{ trans('theme::messages.config.tabs.'.$tab) }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="col-lg-9">
            {{-- Une case décochée n'est pas envoyée : chaque interrupteur a un champ caché à 0 --}}
            <form action="{{ route('admin.themes.config', $theme) }}" method="POST">
                @csrf

                <div class="card shadow mb-4" id="tab-home" data-config-tab>
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ trans('theme::messages.config.home') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="mb-3 col-md-4">
                                <label class="form-label" for="colorInput">{{ trans('theme::messages.config.color') }}</label>
                                <input type="color" class="form-control form-control-color" id="colorInput" name="color" value="{{ old('color', config('theme.color', '#FF7A00')) }}">
                                <small class="form-text">{{ trans('theme::messages.config.color_info') }}</small>
                            </div>

                            <div class="mb-3 col-md-8">
                                <label class="form-label" for="shadeRange">
                                    {{ trans('theme::messages.config.shade') }} :
                                    <output id="shadeValue" for="shadeRange">{{ old('shade', config('theme.shade', 60)) }}</output> %
                                </label>
                                <input type="range" class="form-range" id="shadeRange" name="shade" min="20" max="90" step="5" value="{{ old('shade', config('theme.shade', 60)) }}"
                                       oninput="document.getElementById('shadeValue').value = this.value">
                                <small class="form-text">{{ trans('theme::messages.config.shade_info') }}</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="titleInput">{{ trans('theme::messages.config.title') }}</label>
                            <input type="text" class="form-control" id="titleInput" name="title" value="{{ old('title', config('theme.title')) }}" placeholder="{{ trans('theme::messages.hero.title') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="subtitleInput">{{ trans('theme::messages.config.subtitle') }}</label>
                            <textarea class="form-control" id="subtitleInput" name="subtitle" rows="2" placeholder="{{ trans('theme::messages.hero.subtitle') }}">{{ old('subtitle', config('theme.subtitle')) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="heroLayoutSelect">{{ trans('theme::messages.config.hero_layout') }}</label>
                            <select class="form-select" id="heroLayoutSelect" name="hero_layout">
                                @foreach(['centered', 'cinematic', 'split'] as $layout)
                                    <option value="{{ $layout }}" @selected(old('hero_layout', config('theme.hero_layout', 'centered')) === $layout)>{{ trans('theme::messages.config.hero_layouts.'.$layout) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ trans('theme::messages.config.hero_images') }}</label>
                            @if($images->isEmpty())
                                <p class="text-body-secondary small mb-1">{{ trans('theme::messages.config.hero_images_none') }}</p>
                            @else
                                <div class="row g-2">
                                    @foreach($images as $image)
                                        <div class="col-6 col-md-3">
                                            <input type="checkbox" class="btn-check" name="hero_images[]" value="{{ $image->file }}" id="heroImage{{ $image->id }}" @checked(in_array($image->file, old('hero_images', config('theme.hero_images') ?? []), true))>
                                            <label class="btn btn-outline-primary w-100 p-1" for="heroImage{{ $image->id }}">
                                                <img src="{{ image_url($image->file) }}" alt="" class="w-100 rounded" style="aspect-ratio: 16 / 10; object-fit: cover">
                                                <small class="d-block text-truncate mt-1">{{ $image->name }}</small>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <small class="form-text d-block mt-1">
                                {{ trans('theme::messages.config.hero_images_info') }}
                                <a href="{{ route('admin.images.create') }}">{{ trans('theme::messages.config.image_upload') }}</a>
                            </small>
                        </div>

                        <div class="form-check form-switch">
                            <input type="hidden" name="background_pages" value="0">
                            <input type="checkbox" class="form-check-input" id="background_pagesSwitch" name="background_pages" value="1" @checked(old('background_pages', config('theme.background_pages', true)))>
                            <label class="form-check-label" for="background_pagesSwitch">{{ trans('theme::messages.config.background_pages') }}</label>
                        </div>

                        <label class="form-label mt-4">{{ trans('theme::messages.config.sections_order') }}</label>
                        <ul class="list-group mb-1" data-sortable>
                            @foreach($sectionOrder as $section)
                                <li class="list-group-item d-flex align-items-center gap-2" draggable="true" data-sortable-item>
                                    <i class="bi bi-grip-vertical text-body-secondary" style="cursor: grab"></i>
                                    <input type="hidden" name="sections[]" value="{{ $section }}">
                                    <span class="flex-grow-1">{{ trans('theme::messages.config.section_names.'.$section) }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-move="up"><i class="bi bi-arrow-up"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-move="down"><i class="bi bi-arrow-down"></i></button>
                                </li>
                            @endforeach
                        </ul>
                        <small class="form-text">{{ trans('theme::messages.config.sections_order_info') }}</small>
                    </div>
                </div>

                <div class="card shadow mb-4" id="tab-features" data-config-tab>
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ trans('theme::messages.config.features') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch">
                            <input type="hidden" name="show_features" value="0">
                            <input type="checkbox" class="form-check-input" id="show_featuresSwitch" name="show_features" value="1" @checked(old('show_features', config('theme.show_features', true)))>
                            <label class="form-check-label" for="show_featuresSwitch">{{ trans('theme::messages.config.show_features') }}</label>
                        </div>

                        <div class="mt-3">
                            <label class="form-label" for="featuresLayoutSelect">{{ trans('theme::messages.config.features_layout') }}</label>
                            <select class="form-select" id="featuresLayoutSelect" name="features_layout">
                                @foreach(['list', 'bento', 'showcase'] as $layout)
                                    <option value="{{ $layout }}" @selected(old('features_layout', config('theme.features_layout', 'list')) === $layout)>{{ trans('theme::messages.config.layouts.'.$layout) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="mb-3 col-md-5">
                                <label class="form-label" for="featuresTitleInput">{{ trans('theme::messages.config.section_title') }}</label>
                                <input type="text" class="form-control" id="featuresTitleInput" name="features_title" value="{{ old('features_title', config('theme.features_title')) }}" placeholder="{{ trans('theme::messages.features.title') }}">
                            </div>
                            <div class="mb-3 col-md-7">
                                <label class="form-label" for="featuresSubtitleInput">{{ trans('theme::messages.config.section_subtitle') }}</label>
                                <input type="text" class="form-control" id="featuresSubtitleInput" name="features_subtitle" value="{{ old('features_subtitle', config('theme.features_subtitle')) }}" placeholder="{{ trans('theme::messages.features.subtitle') }}">
                            </div>
                        </div>

                        <label class="form-label">{{ trans('theme::messages.config.features_items') }}</label>
                        <div data-repeater="features" data-max="9" data-items="{{ json_encode($features) }}">
                            <div data-rows></div>
                            <button type="button" class="btn btn-sm btn-success" data-repeater-add>
                                <i class="bi bi-plus-lg"></i> {{ trans('theme::messages.config.item_add') }}
                            </button>
                            <template>
                                <div class="border rounded p-3 mb-2" data-row>
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bi" data-icon-preview></i></span>
                                                <input type="text" class="form-control" data-field="icon" placeholder="{{ trans('theme::messages.config.item_icon') }}" required>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" data-field="title" placeholder="{{ trans('theme::messages.config.item_title') }}" required>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button" class="btn btn-outline-danger" data-repeater-remove><i class="bi bi-x-lg"></i></button>
                                        </div>
                                    </div>
                                    <textarea class="form-control" rows="2" data-field="text" placeholder="{{ trans('theme::messages.config.item_text') }}" required></textarea>
                                    <select class="form-select mt-2" data-field="image">
                                        <option value="">{{ trans('theme::messages.config.item_no_image') }}</option>
                                        @foreach($images as $image)
                                            <option value="{{ $image->file }}">{{ $image->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </template>
                        </div>
                        <small class="form-text d-block mt-2">
                            {{ trans('theme::messages.config.features_info') }}
                            <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener noreferrer">{{ trans('theme::messages.config.icons_link') }}</a>
                            ·
                            <a href="{{ route('admin.images.create') }}">{{ trans('theme::messages.config.image_upload') }}</a>
                        </small>
                    </div>
                </div>

                <div class="card shadow mb-4" id="tab-join" data-config-tab>
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ trans('theme::messages.config.join') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch">
                            <input type="hidden" name="show_join" value="0">
                            <input type="checkbox" class="form-check-input" id="show_joinSwitch" name="show_join" value="1" @checked(old('show_join', config('theme.show_join', true)))>
                            <label class="form-check-label" for="show_joinSwitch">{{ trans('theme::messages.config.show_join') }}</label>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label" for="joinTitleInput">{{ trans('theme::messages.config.section_title') }}</label>
                            <input type="text" class="form-control" id="joinTitleInput" name="join_title" value="{{ old('join_title', config('theme.join_title')) }}" placeholder="{{ trans('theme::messages.join.title') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="joinLayoutSelect">{{ trans('theme::messages.config.join_layout') }}</label>
                            <select class="form-select" id="joinLayoutSelect" name="join_layout">
                                @foreach(['cards', 'timeline', 'split'] as $layout)
                                    <option value="{{ $layout }}" @selected(old('join_layout', config('theme.join_layout', 'cards')) === $layout)>{{ trans('theme::messages.config.join_layouts.'.$layout) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <label class="form-label">{{ trans('theme::messages.config.join_items') }}</label>
                        <div data-repeater="join_steps" data-max="4" data-items="{{ json_encode($joinSteps) }}">
                            <div data-rows></div>
                            <button type="button" class="btn btn-sm btn-success" data-repeater-add>
                                <i class="bi bi-plus-lg"></i> {{ trans('theme::messages.config.item_add') }}
                            </button>
                            <template>
                                <div class="border rounded p-3 mb-2" data-row>
                                    <div class="d-flex gap-2 mb-2">
                                        <input type="text" class="form-control" data-field="title" placeholder="{{ trans('theme::messages.config.item_title') }}" required>
                                        <button type="button" class="btn btn-outline-danger" data-repeater-remove><i class="bi bi-x-lg"></i></button>
                                    </div>
                                    <textarea class="form-control" rows="2" data-field="text" placeholder="{{ trans('theme::messages.config.item_text') }}" required></textarea>
                                    <select class="form-select mt-2" data-field="image">
                                        <option value="">{{ trans('theme::messages.config.item_no_image') }}</option>
                                        @foreach($images as $image)
                                            <option value="{{ $image->file }}">{{ $image->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </template>
                        </div>
                        <small class="form-text d-block mt-2">{{ trans('theme::messages.config.join_info') }}</small>
                    </div>
                </div>

                @foreach($plugins as $plugin)
                    <div class="card shadow mb-4" id="tab-{{ $plugin }}" data-config-tab>
                        <div class="card-header">
                            <h5 class="card-title mb-0">{{ trans('theme::messages.config.tabs.'.$plugin) }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-body-secondary">
                                {{ trans('theme::messages.config.plugin_info') }} {{ trans('theme::messages.config.'.$plugin.'_info') }}
                            </p>

                            <div class="form-check form-switch">
                                <input type="hidden" name="show_{{ $plugin }}" value="0">
                                <input type="checkbox" class="form-check-input" id="show_{{ $plugin }}Switch" name="show_{{ $plugin }}" value="1" @checked(old('show_'.$plugin, config('theme.show_'.$plugin, true)))>
                                <label class="form-check-label" for="show_{{ $plugin }}Switch">{{ trans('theme::messages.config.show_plugin') }}</label>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-8">
                                    <label class="form-label" for="{{ $plugin }}TitleInput">{{ trans('theme::messages.config.section_title') }}</label>
                                    <input type="text" class="form-control" id="{{ $plugin }}TitleInput" name="{{ $plugin }}_title" value="{{ old($plugin.'_title', config('theme.'.$plugin.'_title')) }}" placeholder="{{ trans('theme::messages.'.$plugin.'.title') }}">
                                </div>

                                @isset($pluginCounts[$plugin])
                                    @php([$default, $max] = $pluginCounts[$plugin])
                                    <div class="col-md-4">
                                        <label class="form-label" for="{{ $plugin }}CountInput">{{ trans('theme::messages.config.plugin_count') }}</label>
                                        <input type="number" class="form-control" id="{{ $plugin }}CountInput" name="{{ $plugin }}_count" min="1" max="{{ $max }}" value="{{ old($plugin.'_count', config('theme.'.$plugin.'_count', $default)) }}">
                                    </div>
                                @endisset
                            </div>

                            @if($plugin === 'shop')
                                {{-- Pages de catégorie de la boutique : disposition générale, et catégories en grille tarifaire --}}
                                <hr class="my-4">
                                <div class="mb-3">
                                    <label class="form-label" for="shopLayoutSelect">{{ trans('theme::messages.config.shop_layout') }}</label>
                                    <select class="form-select" id="shopLayoutSelect" name="shop_layout">
                                        @foreach(['cards', 'list', 'pricing'] as $layout)
                                            <option value="{{ $layout }}" @selected(old('shop_layout', config('theme.shop_layout', 'cards')) === $layout)>{{ trans('theme::messages.config.shop_layouts.'.$layout) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <label class="form-label">{{ trans('theme::messages.config.shop_pricing_categories') }}</label>
                                @foreach(\Azuriom\Plugin\Shop\Models\Category::orderBy('position')->get() as $shopCategory)
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="shopPricing{{ $shopCategory->id }}" name="shop_pricing_categories[]" value="{{ $shopCategory->id }}"
                                               @checked(in_array($shopCategory->id, old('shop_pricing_categories', config('theme.shop_pricing_categories') ?? [])))>
                                        <label class="form-check-label" for="shopPricing{{ $shopCategory->id }}">{{ $shopCategory->name }}</label>
                                    </div>
                                @endforeach
                                <small class="form-text">{{ trans('theme::messages.config.shop_pricing_info') }}</small>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="card shadow mb-4" id="tab-footer" data-config-tab>
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ trans('theme::messages.config.footer') }}</h5>
                    </div>
                    <div class="card-body">
                        <label class="form-label">{{ trans('theme::messages.config.footer_links') }}</label>
                        <div data-repeater="footer_links" data-max="8" data-items="{{ json_encode($footerLinks) }}">
                            <div data-rows></div>
                            <button type="button" class="btn btn-sm btn-success" data-repeater-add>
                                <i class="bi bi-plus-lg"></i> {{ trans('theme::messages.config.link_add') }}
                            </button>
                            <template>
                                <div class="input-group mb-2" data-row>
                                    <input type="text" class="form-control" data-field="name" placeholder="{{ trans('theme::messages.config.link_name') }}" required>
                                    <input type="url" class="form-control" data-field="url" placeholder="https://..." required>
                                    <button type="button" class="btn btn-outline-danger" data-repeater-remove><i class="bi bi-x-lg"></i></button>
                                </div>
                            </template>
                        </div>
                        <small class="form-text d-block mt-2 mb-3">{{ trans('theme::messages.config.footer_links_info') }}</small>

                        <div class="form-check form-switch">
                            <input type="hidden" name="show_mojang" value="0">
                            <input type="checkbox" class="form-check-input" id="show_mojangSwitch" name="show_mojang" value="1" @checked(old('show_mojang', config('theme.show_mojang', true)))>
                            <label class="form-check-label" for="show_mojangSwitch">{{ trans('theme::messages.config.show_mojang') }}</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection

@push('footer-scripts')
    <script>
        // Rubriques : une seule visible à la fois, retenue dans l'adresse (#tab-...) pour survivre à la sauvegarde
        (() => {
            const tabs = document.querySelectorAll('[data-config-tab]');
            const links = document.querySelectorAll('[data-config-tab-link]');

            const show = (id) => {
                tabs.forEach((tab) => tab.classList.toggle('d-none', tab.id !== id));
                links.forEach((link) => link.classList.toggle('active', link.hash === '#' + id));
                history.replaceState(null, '', '#' + id);
            };

            links.forEach((link) => link.addEventListener('click', (event) => {
                event.preventDefault();
                show(link.hash.slice(1));
            }));

            // Un champ invalide dans une rubrique cachée : on l'affiche, sinon le navigateur bloque sans rien dire
            document.querySelector('form').addEventListener('invalid', (event) => {
                const tab = event.target.closest('[data-config-tab]');
                if (tab && tab.classList.contains('d-none')) {
                    show(tab.id);
                }
            }, true);

            const current = document.getElementById(location.hash.slice(1));
            show(current && current.matches('[data-config-tab]') ? current.id : tabs[0].id);
        })();

        // Ordre des sections : glisser-déposer, ou flèches haut/bas
        document.querySelectorAll('[data-sortable]').forEach((list) => {
            let dragged = null;

            list.addEventListener('click', (event) => {
                const button = event.target.closest('[data-move]');
                if (button) {
                    const item = button.closest('[data-sortable-item]');
                    if (button.dataset.move === 'up') {
                        item.previousElementSibling?.before(item);
                    } else {
                        item.nextElementSibling?.after(item);
                    }
                }
            });

            list.addEventListener('dragstart', (event) => {
                dragged = event.target.closest('[data-sortable-item]');
                dragged.classList.add('opacity-50');
            });

            list.addEventListener('dragend', () => {
                dragged?.classList.remove('opacity-50');
                dragged = null;
            });

            list.addEventListener('dragover', (event) => {
                const over = event.target.closest('[data-sortable-item]');
                event.preventDefault();
                if (dragged && over && over !== dragged) {
                    const box = over.getBoundingClientRect();
                    if (event.clientY < box.top + box.height / 2) {
                        over.before(dragged);
                    } else {
                        over.after(dragged);
                    }
                }
            });
        });

        // Listes modifiables : chaque ligne vient du <template> de sa liste, nommée liste[index][champ]
        document.querySelectorAll('[data-repeater]').forEach((repeater) => {
            const rows = repeater.querySelector('[data-rows]');
            const template = repeater.querySelector('template');
            const add = repeater.querySelector('[data-repeater-add]');
            const max = Number(repeater.dataset.max);
            let next = 0;

            const refresh = () => (add.disabled = rows.children.length >= max);
            const showIcon = (input) => {
                const preview = input.closest('[data-row]').querySelector('[data-icon-preview]');
                preview.className = 'bi bi-' + input.value.trim().replace(/^bi-/, '');
            };

            const append = (values = {}) => {
                const row = template.content.firstElementChild.cloneNode(true);
                const index = next++;
                rows.append(row);
                row.querySelectorAll('[data-field]').forEach((input) => {
                    input.name = `${repeater.dataset.repeater}[${index}][${input.dataset.field}]`;
                    input.value = values[input.dataset.field] ?? '';
                    if (input.dataset.field === 'icon') {
                        showIcon(input);
                    }
                });
                refresh();
            };

            JSON.parse(repeater.dataset.items || '[]').forEach((item) => append(item));
            add.addEventListener('click', () => append());

            rows.addEventListener('click', (event) => {
                const remove = event.target.closest('[data-repeater-remove]');
                if (remove) {
                    remove.closest('[data-row]').remove();
                    refresh();
                }
            });

            rows.addEventListener('input', (event) => {
                if (event.target.dataset.field === 'icon') {
                    showIcon(event.target);
                }
            });
        });
    </script>
@endpush
