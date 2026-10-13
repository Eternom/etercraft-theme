{{-- Disposition « mosaïque » : la première carte est mise en avant, un halo suit la souris --}}
<div class="section-head text-center" data-reveal>
    <span class="eyebrow">{{ site_name() }}</span>
    <h2>{{ $title }}</h2>
    <p>{{ $subtitle }}</p>
</div>

<div class="bento">
    @foreach($features as $index => $feature)
        <article class="bento-card @if($index === 0) bento-card-lead @endif" data-reveal data-spotlight>
            <i class="bi bi-{{ Str::after($feature['icon'], 'bi-') }} bento-glyph" aria-hidden="true"></i>
            <div>
                <h3>{{ $feature['title'] }}</h3>
                <p>{{ $feature['text'] }}</p>
            </div>
        </article>
    @endforeach
</div>
