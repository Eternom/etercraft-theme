@extends('admin.layouts.admin')

@section('title', 'EterCraft')

@php
    // Les listes sont construites côté navigateur à partir de ces valeurs (voir le script en bas)
    $features = array_values(old('features', config('theme.features') ?: trans('theme::messages.features.items')));
    $joinSteps = array_values(old('join_steps', config('theme.join_steps') ?: trans('theme::messages.join.steps')));
    $footerLinks = array_values(old('footer_links', config('theme.footer_links') ?? []));
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

    {{-- Une case décochée n'est pas envoyée : chaque interrupteur a un champ caché à 0 --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.themes.config', $theme) }}" method="POST">
        @csrf

        <div class="card shadow mb-4">
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
                    <input type="text" class="form-control" id="titleInput" name="title" value="{{ old('title', config('theme.title')) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label" for="subtitleInput">{{ trans('theme::messages.config.subtitle') }}</label>
                    <textarea class="form-control" id="subtitleInput" name="subtitle" rows="2">{{ old('subtitle', config('theme.subtitle')) }}</textarea>
                </div>

                <div class="form-check form-switch">
                    <input type="hidden" name="background_pages" value="0">
                    <input type="checkbox" class="form-check-input" id="background_pagesSwitch" name="background_pages" value="1" @checked(old('background_pages', config('theme.background_pages', true)))>
                    <label class="form-check-label" for="background_pagesSwitch">{{ trans('theme::messages.config.background_pages') }}</label>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ trans('theme::messages.config.features') }}</h5>
            </div>
            <div class="card-body">
                <div class="form-check form-switch">
                    <input type="hidden" name="show_features" value="0">
                    <input type="checkbox" class="form-check-input" id="show_featuresSwitch" name="show_features" value="1" @checked(old('show_features', config('theme.show_features', true)))>
                    <label class="form-check-label" for="show_featuresSwitch">{{ trans('theme::messages.config.show_features') }}</label>
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
                        </div>
                    </template>
                </div>
                <small class="form-text d-block mt-2">
                    {{ trans('theme::messages.config.features_info') }}
                    <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener noreferrer">{{ trans('theme::messages.config.icons_link') }}</a>
                </small>
            </div>
        </div>

        <div class="card shadow mb-4">
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
                        </div>
                    </template>
                </div>
                <small class="form-text d-block mt-2">{{ trans('theme::messages.config.join_info') }}</small>
            </div>
        </div>

        <div class="card shadow mb-4">
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
@endsection

@push('footer-scripts')
    <script>
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
