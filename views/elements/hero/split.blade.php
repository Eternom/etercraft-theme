{{-- Hero « partagé » : texte à gauche, images dans un cadre à droite --}}
<section class="hero hero-split">
    <div class="hero-glow"></div>

    <div class="container hero-content">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                @include('elements.hero.status')

                <h1 class="hero-title" data-reveal>{{ site_name() }}</h1>

                <p class="hero-lead" data-reveal>{{ config('theme.title') ?: trans('theme::messages.hero.title') }}</p>
                <p class="hero-text" data-reveal>{{ config('theme.subtitle') ?: trans('theme::messages.hero.subtitle') }}</p>

                @include('elements.hero.actions')
            </div>

            <div class="col-lg-6" data-reveal>
                <div class="hero-frame">
                    @include('elements.hero.slides')
                    @unless($heroImages)
                        <span class="hero-frame-mark">{{ site_name() }}</span>
                    @endunless
                </div>
            </div>
        </div>
    </div>

    <a href="#discover" class="hero-scroll" aria-label="{{ trans('theme::messages.hero.discover') }}">
        <i class="bi bi-chevron-down"></i>
    </a>
</section>
