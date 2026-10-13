@if(config('theme.show_join', true))
    <section id="join" class="section">
        <div class="container">
            @include('elements.join.'.(in_array(config('theme.join_layout'), ['timeline', 'split'], true) ? config('theme.join_layout') : 'cards'), [
                'title' => config('theme.join_title') ?: trans('theme::messages.join.title'),
                'steps' => array_values(config('theme.join_steps') ?: trans('theme::messages.join.steps')),
            ])
        </div>
    </section>
@endif
