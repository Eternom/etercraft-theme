{{-- Hero « partagé » : texte à gauche, images dans un cadre à droite --}}
<section class="hero hero-split">
    <div class="hero-glow"></div>

    <div class="container hero-content">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                @include('elements.hero.status')

                <h1 class="hero-title" data-reveal>{{ site_name() }}</h1>

                @if(config('theme.title'))
                    <p class="hero-lead" data-reveal>{{ config('theme.title') }}</p>
                @endif
                @if(config('theme.subtitle'))
                    <p class="hero-text" data-reveal>{{ config('theme.subtitle') }}</p>
                @endif

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
