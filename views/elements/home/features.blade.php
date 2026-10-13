@if(config('theme.show_features', true))
    <section id="features" class="section">
        <div class="container">
            @include('elements.features.'.(in_array(config('theme.features_layout'), ['bento', 'showcase'], true) ? config('theme.features_layout') : 'list'), [
                'title' => config('theme.features_title') ?: trans('theme::messages.features.title'),
                'subtitle' => config('theme.features_subtitle') ?: trans('theme::messages.features.subtitle'),
                'features' => array_values(config('theme.features') ?: trans('theme::messages.features.items')),
            ])
        </div>
    </section>
@endif
