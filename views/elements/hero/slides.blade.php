{{-- Images du hero : la première est tirée au hasard, les suivantes défilent en fondu (theme.js) --}}
@if($heroImages)
    <div class="hero-slides" style="--tc-shade: {{ config('theme.shade', 60) / 100 }}" @if(count($heroImages) > 1) data-slideshow @endif>
        @foreach($heroImages as $index => $image)
            <div class="hero-slide @if($index === 0) active @endif" style="background-image: url('{{ image_url($image) }}')"></div>
        @endforeach
    </div>
@endif
