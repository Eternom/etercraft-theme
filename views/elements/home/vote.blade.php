{{-- Section Vote (plugin Vote) : podium du mois et bouton pour voter --}}
@if(plugins()->isEnabled('vote') && config('theme.show_vote', true))
    <section id="vote" class="section">
        <div class="container">
            <div class="vote-showcase" data-reveal>
                <div>
                    <span class="eyebrow">{{ site_name() }}</span>
                    <h2>{{ config('theme.vote_title') ?: trans('theme::messages.vote.title') }}</h2>
                    <p>{{ trans('theme::messages.vote.text') }}</p>
                    <a href="{{ route('vote.home') }}" class="btn btn-primary btn-lg btn-glow">
                        <i class="bi bi-hand-thumbs-up"></i> {{ trans('theme::messages.hero.vote') }}
                    </a>
                </div>

                @include('elements.vote-podium', [
                    'voters' => \Azuriom\Plugin\Vote\Models\Vote::getTopVoters(now()->startOfMonth())->take(3),
                ])
            </div>
        </div>
    </section>
@endif
