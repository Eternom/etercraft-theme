{{-- Hero « centré » : tout au milieu, images en fond --}}
<section class="hero">
    @include('elements.hero.slides')
    <div class="hero-glow"></div>

    <div class="container hero-content text-center">
        @include('elements.hero.status')

        <h1 class="hero-title" data-reveal>{{ site_name() }}</h1>

        <p class="hero-lead" data-reveal>{{ config('theme.title') ?: trans('theme::messages.hero.title') }}</p>
        <p class="hero-text mx-auto" data-reveal>{{ config('theme.subtitle') ?: trans('theme::messages.hero.subtitle') }}</p>

        @include('elements.hero.actions')
    </div>

    <a href="#discover" class="hero-scroll" aria-label="{{ trans('theme::messages.hero.discover') }}">
        <i class="bi bi-chevron-down"></i>
    </a>
</section>
