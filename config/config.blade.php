@extends('admin.layouts.admin')

@section('title', 'EterCraft')

@php
    $footerLinks = old('footer_links', config('theme.footer_links', []));
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
                        <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" id="colorInput" name="color" value="{{ old('color', config('theme.color', '#FF7A00')) }}">

                        @error('color')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror

                        <small class="form-text">{{ trans('theme::messages.config.color_info') }}</small>
                    </div>

                    <div class="mb-3 col-md-8">
                        <label class="form-label" for="shadeRange">{{ trans('theme::messages.config.shade') }}</label>
                        <input type="range" class="form-range" id="shadeRange" name="shade" min="20" max="90" step="5" value="{{ old('shade', config('theme.shade', 60)) }}">
                        <small class="form-text">{{ trans('theme::messages.config.shade_info') }}</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="titleInput">{{ trans('theme::messages.config.title') }}</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="titleInput" name="title" value="{{ old('title', config('theme.title')) }}">

                    @error('title')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="subtitleInput">{{ trans('theme::messages.config.subtitle') }}</label>
                    <textarea class="form-control @error('subtitle') is-invalid @enderror" id="subtitleInput" name="subtitle" rows="2">{{ old('subtitle', config('theme.subtitle')) }}</textarea>

                    @error('subtitle')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Une case décochée n'est pas envoyée : le champ caché garde la valeur 0 --}}
                @foreach(['background_pages', 'show_features', 'show_join'] as $toggle)
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="{{ $toggle }}" value="0">
                        <input type="checkbox" class="form-check-input" id="{{ $toggle }}Switch" name="{{ $toggle }}" value="1" @checked(old($toggle, config('theme.'.$toggle, true)))>
                        <label class="form-check-label" for="{{ $toggle }}Switch">{{ trans('theme::messages.config.'.$toggle) }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ trans('theme::messages.config.footer') }}</h5>
            </div>
            <div class="card-body">
                <label class="form-label">{{ trans('theme::messages.config.footer_links') }}</label>
                <div id="footerLinks">
                    @foreach($footerLinks as $index => $link)
                        <div class="input-group mb-2" data-footer-link>
                            <input type="text" class="form-control @error('footer_links.'.$index.'.name') is-invalid @enderror" name="footer_links[{{ $index }}][name]" value="{{ $link['name'] ?? '' }}" placeholder="{{ trans('theme::messages.config.link_name') }}">
                            <input type="url" class="form-control @error('footer_links.'.$index.'.url') is-invalid @enderror" name="footer_links[{{ $index }}][url]" value="{{ $link['url'] ?? '' }}" placeholder="https://...">
                            <button type="button" class="btn btn-outline-danger" data-footer-link-remove><i class="bi bi-x-lg"></i></button>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-sm btn-success" id="footerLinkAdd">
                    <i class="bi bi-plus-lg"></i> {{ trans('theme::messages.config.link_add') }}
                </button>
                <small class="form-text d-block mt-2 mb-3">{{ trans('theme::messages.config.footer_links_info') }}</small>

                <div class="form-check form-switch">
                    <input type="hidden" name="show_mojang" value="0">
                    <input type="checkbox" class="form-check-input" id="showMojangSwitch" name="show_mojang" value="1" @checked(old('show_mojang', config('theme.show_mojang', true)))>
                    <label class="form-check-label" for="showMojangSwitch">{{ trans('theme::messages.config.show_mojang') }}</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
        </button>
    </form>

    <template id="footerLinkTemplate">
        <div class="input-group mb-2" data-footer-link>
            <input type="text" class="form-control" data-name="name" placeholder="{{ trans('theme::messages.config.link_name') }}">
            <input type="url" class="form-control" data-name="url" placeholder="https://...">
            <button type="button" class="btn btn-outline-danger" data-footer-link-remove><i class="bi bi-x-lg"></i></button>
        </div>
    </template>
@endsection

@push('footer-scripts')
    <script>
        (() => {
            const list = document.getElementById('footerLinks');
            const template = document.getElementById('footerLinkTemplate');
            // Index jamais réutilisé : deux lignes ne partagent pas le même nom de champ
            let next = Date.now();

            document.getElementById('footerLinkAdd').addEventListener('click', () => {
                const row = template.content.firstElementChild.cloneNode(true);
                const index = next++;
                row.querySelectorAll('[data-name]').forEach((input) => {
                    input.name = `footer_links[${index}][${input.dataset.name}]`;
                });
                list.append(row);
            });

            list.addEventListener('click', (event) => {
                const remove = event.target.closest('[data-footer-link-remove]');
                if (remove) {
                    remove.closest('[data-footer-link]').remove();
                }
            });
        })();
    </script>
@endpush
