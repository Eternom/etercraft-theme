{{-- Disposition « éditoriale » : titre fixe à gauche, liste numérotée à droite --}}
<div class="row g-5">
    <div class="col-lg-4">
        <div class="features-intro" data-reveal>
            <span class="eyebrow">{{ site_name() }}</span>
            <h2>{{ $title }}</h2>
            <p>{{ $subtitle }}</p>
        </div>
    </div>

    <div class="col-lg-8">
        <ol class="feature-list">
            @foreach($features as $index => $feature)
                <li class="feature-row" data-reveal>
                    <span class="feature-index">{{ sprintf('%02d', $index + 1) }}</span>
                    <div>
                        <h3>{{ $feature['title'] }}</h3>
                        <p>{{ $feature['text'] }}</p>
                    </div>
                    <i class="bi bi-{{ Str::after($feature['icon'], 'bi-') }} feature-glyph" aria-hidden="true"></i>
                </li>
            @endforeach
        </ol>
    </div>
</div>
