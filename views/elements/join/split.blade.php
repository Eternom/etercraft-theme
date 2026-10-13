{{-- Disposition « partagée » : grande image à gauche qui suit l'étape survolée, étapes à droite --}}
@php
    $images = array_filter(array_map(fn ($step) => $step['image'] ?? null, $steps));
@endphp

<div class="row g-5 align-items-center" data-join-split>
    <div class="col-lg-6">
        <div class="join-visual" data-reveal>
            @foreach($images as $index => $image)
                <div class="join-visual-image @if($loop->first) active @endif" data-join-image="{{ $index }}" style="background-image: url('{{ image_url($image) }}')"></div>
            @endforeach
            <div>
                <span class="eyebrow">{{ site_name() }}</span>
                <h2>{{ $title }}</h2>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <ol class="join-steps">
            @foreach($steps as $index => $step)
                <li class="join-step @if($index === 0) active @endif" data-join-step="{{ $index }}" tabindex="0" data-reveal>
                    <span class="join-step-number">{{ $index + 1 }}</span>
                    <div>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['text'] }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</div>
