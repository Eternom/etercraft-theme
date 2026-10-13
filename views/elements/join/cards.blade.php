{{-- Disposition « cartes » : une carte par étape, grand numéro en filigrane --}}
<div class="section-head text-center" data-reveal>
    <span class="eyebrow">{{ site_name() }}</span>
    <h2>{{ $title }}</h2>
</div>

<div class="row g-4">
    @foreach($steps as $index => $step)
        <div class="col-md-6 col-lg" data-reveal>
            <div class="glass-card step-card h-100">
                @if(! empty($step['image']))
                    <div class="card-image" style="background-image: url('{{ image_url($step['image']) }}')"></div>
                @endif
                <span class="step-number">{{ $index + 1 }}</span>
                <h3>{{ $step['title'] }}</h3>
                <p>{{ $step['text'] }}</p>
            </div>
        </div>
    @endforeach
</div>
