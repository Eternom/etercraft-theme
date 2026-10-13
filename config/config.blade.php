@extends('admin.layouts.admin')

@section('title', 'EterCraft')

@section('content')
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.themes.config', $theme) }}" method="POST">
                @csrf

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
                        <label class="form-label" for="discordInput">{{ trans('theme::messages.config.discord') }}</label>
                        <input type="url" class="form-control @error('discord') is-invalid @enderror" id="discordInput" name="discord" value="{{ old('discord', config('theme.discord')) }}" placeholder="https://discord.gg/...">

                        @error('discord')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror

                        <small class="form-text">{{ trans('theme::messages.config.discord_info') }}</small>
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

                <p class="text-body-secondary small">{{ trans('theme::messages.config.background_info') }}</p>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection
