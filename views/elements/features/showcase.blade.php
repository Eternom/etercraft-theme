{{-- Disposition « vitrine » : les titres à gauche, l'élément survolé ou cliqué en grand à droite --}}
<div class="row g-5 align-items-center" data-showcase>
    <div class="col-lg-5">
        <div class="mb-4" data-reveal>
            <span class="eyebrow">{{ site_name() }}</span>
            <h2 class="showcase-title">{{ $title }}</h2>
            <p class="text-body-secondary mb-0">{{ $subtitle }}</p>
        </div>

        <div class="showcase-tabs">
            @foreach($features as $index => $feature)
                <button type="button" class="showcase-tab @if($index === 0) active @endif" data-showcase-tab="{{ $index }}" data-reveal>
                    <span class="feature-index">{{ sprintf('%02d', $index + 1) }}</span>
                    <span class="showcase-name">{{ $feature['title'] }}</span>
                    <i class="bi bi-arrow-right showcase-arrow" aria-hidden="true"></i>
                    {{-- Sur téléphone il n'y a pas de panneau : le texte est sous le titre --}}
                    <span class="showcase-text d-lg-none">{{ $feature['text'] }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <div class="col-lg-7 d-none d-lg-block">
        <div class="showcase-stage" data-reveal>
            @foreach($features as $index => $feature)
                <div class="showcase-panel @if($index === 0) active @endif" data-showcase-panel="{{ $index }}">
                    @if(! empty($feature['image']))
                        <div class="card-image" style="background-image: url('{{ image_url($feature['image']) }}')"></div>
                    @endif
                    <i class="bi bi-{{ Str::after($feature['icon'], 'bi-') }} showcase-glyph" aria-hidden="true"></i>
                    <span class="feature-index">{{ sprintf('%02d', $index + 1) }} / {{ sprintf('%02d', count($features)) }}</span>
                    <h3>{{ $feature['title'] }}</h3>
                    <p>{{ $feature['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
