@if(Route::has('vote.home'))
    <section id="vote" class="section">
        <div class="container">
            <div class="cta-card" data-reveal>
                <div>
                    <h2>{{ trans('theme::messages.vote.title') }}</h2>
                    <p class="mb-0">{{ trans('theme::messages.vote.text') }}</p>
                </div>
                <a href="{{ route('vote.home') }}" class="btn btn-primary btn-lg btn-glow flex-shrink-0">
                    <i class="bi bi-hand-thumbs-up"></i> {{ trans('theme::messages.hero.vote') }}
                </a>
            </div>
        </div>
    </section>
@endif
