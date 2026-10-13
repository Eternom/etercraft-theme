{{-- Hero « centré » : tout au milieu, images en fond --}}
<section class="hero">
    @include('elements.hero.slides')
    <div class="hero-glow"></div>

    <div class="container hero-content text-center">
        @include('elements.hero.status')

        <h1 class="hero-title" data-reveal>{{ site_name() }}</h1>

        @if(config('theme.title'))
            <p class="hero-lead" data-reveal>{{ config('theme.title') }}</p>
        @endif
        @if(config('theme.subtitle'))
            <p class="hero-text mx-auto" data-reveal>{{ config('theme.subtitle') }}</p>
        @endif

        @include('elements.hero.actions')
    </div>

    <a href="#discover" class="hero-scroll" aria-label="{{ trans('theme::messages.hero.discover') }}">
        <i class="bi bi-chevron-down"></i>
    </a>
</section>
