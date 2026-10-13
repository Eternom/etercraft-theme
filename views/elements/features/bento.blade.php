{{-- Disposition « mosaïque » : une grande carte tirée au hasard à chaque chargement, un halo suit la souris --}}
@php
    // La grande carte est choisie parmi les éléments qui ont une image (sinon parmi tous) et passe en tête
    $withImage = array_keys(array_filter($features, fn ($feature) => ! empty($feature['image'])));
    $lead = $withImage ? $withImage[array_rand($withImage)] : array_rand($features);
    $features = [$features[$lead], ...array_values(array_diff_key($features, [$lead => true]))];
@endphp

<div class="section-head text-center" data-reveal>
    <span class="eyebrow">{{ site_name() }}</span>
    <h2>{{ $title }}</h2>
    <p>{{ $subtitle }}</p>
</div>

<div class="bento">
    @foreach($features as $index => $feature)
        <article class="bento-card @if($index === 0) bento-card-lead @endif" data-reveal data-spotlight>
            @if(! empty($feature['image']))
                <div class="card-image" style="background-image: url('{{ image_url($feature['image']) }}')"></div>
            @endif
            <i class="bi bi-{{ Str::after($feature['icon'], 'bi-') }} bento-glyph" aria-hidden="true"></i>
            <div>
                <h3>{{ $feature['title'] }}</h3>
                <p>{{ $feature['text'] }}</p>
            </div>
        </article>
    @endforeach
</div>
