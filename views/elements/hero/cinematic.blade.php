{{-- Hero « cinéma » : image plein écran, texte en bas à gauche, repères du diaporama en bas à droite --}}
<section class="hero hero-cinematic">
    @include('elements.hero.slides')
    <div class="hero-glow"></div>

    <div class="container hero-content">
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

    @if(count($heroImages) > 1)
        <div class="hero-dots" aria-hidden="true">
            @foreach($heroImages as $index => $image)
                <span class="hero-dot @if($index === 0) active @endif"></span>
            @endforeach
        </div>
    @endif
</section>
