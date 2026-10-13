{{-- Disposition « frise » : étapes reliées par une ligne, horizontale sur ordinateur, verticale sur téléphone --}}
@php
    $withImages = collect($steps)->contains(fn ($step) => ! empty($step['image']));
@endphp

<div class="section-head text-center" data-reveal>
    <span class="eyebrow">{{ site_name() }}</span>
    <h2>{{ $title }}</h2>
</div>

<ol class="timeline">
    @foreach($steps as $index => $step)
        <li class="timeline-step" data-reveal>
            <span class="timeline-dot">{{ $index + 1 }}</span>
            @if(! empty($step['image']))
                <div class="timeline-image" style="background-image: url('{{ image_url($step['image']) }}')"></div>
            @elseif($withImages)
                <div class="timeline-image timeline-image-empty" aria-hidden="true">{{ $index + 1 }}</div>
            @endif
            <h3>{{ $step['title'] }}</h3>
            <p>{{ $step['text'] }}</p>
        </li>
    @endforeach
</ol>
