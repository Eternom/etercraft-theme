{{--
    Page du plugin Vote, habillée par le thème (remplace vote::index).
    Le script du plugin (vote.js) s'appuie sur ces repères, à conserver tels quels : #vote-card, [data-vote-step],
    #voteNameForm, #stepNameInput, .load-spinner, [data-vote-id|url|time], .vote-timer, #server-select,
    #vote-goal, .progress-bar, #goal-text, et #status-message (dans elements.session-alerts).
--}}
@extends('layouts.app')

@section('title', trans('vote::messages.title'))

@section('content')
    <h1>{{ trans('vote::messages.sections.vote') }}</h1>

    <div class="row g-4 mb-4">
        <div class="col-lg-7">
            <div class="glass-card vote-panel h-100" id="vote-card">
                <div class="spinner-parent">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>

                <h2 class="vote-panel-title">{{ trans('theme::messages.vote.sites') }}</h2>

                {{-- Étape 1 : choisir le pseudo (visiteur non connecté) --}}
                <div class="@auth d-none @endauth" data-vote-step="1">
                    <form action="{{ route('vote.verify-user', '/') }}" id="voteNameForm">
                        @if(! $authRequired)
                            <div class="input-group">
                                <input type="text" id="stepNameInput" name="name" class="form-control" value="{{ $name }}"
                                       placeholder="{{ trans('messages.fields.name') }}" required>
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('messages.actions.continue') }}
                                    <span class="d-none spinner-border spinner-border-sm load-spinner" role="status"></span>
                                </button>
                            </div>
                        @else
                            <p class="text-body-secondary">{{ trans('vote::messages.errors.auth') }}</p>
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right"></i> {{ trans('auth.login') }}
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Étape 2 : les sites de vote, avec le délai restant avant le prochain vote --}}
                <div class="@guest d-none @endguest" data-vote-step="2">
                    <div class="vote-sites">
                        @forelse($sites as $site)
                            <a class="btn vote-site" href="{{ $site->url }}" target="_blank" rel="noopener noreferrer"
                               data-vote-id="{{ $site->id }}"
                               data-vote-url="{{ route('vote.vote', $site) }}"
                               @auth data-vote-time="{{ $site->getNextVoteTime($user, $request)?->valueOf() }}" @endauth>
                                <span class="vote-site-name">{{ $site->name }}</span>
                                <span class="vote-timer"></span>
                                <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                            </a>
                        @empty
                            <p class="text-body-secondary mb-0">{{ trans('vote::messages.errors.site') }}</p>
                        @endforelse
                    </div>
                </div>

                <div class="d-none" data-vote-step="3">
                    <p id="vote-result"></p>
                </div>

                <div class="d-none" data-vote-step="server">
                    <p>{{ trans('vote::messages.server') }}</p>
                    <div id="server-select" class="d-flex flex-wrap gap-2"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="glass-card vote-panel h-100">
                <h2 class="vote-panel-title">{{ trans('vote::messages.sections.top') }}</h2>

                @include('elements.vote-podium', ['voters' => $votes->take(3)])

                @if($votes->count() > 3)
                    <ol class="vote-ranking" start="4">
                        @foreach($votes->slice(3) as $position => $vote)
                            <li>
                                <span class="vote-ranking-position">{{ $position }}</span>
                                <span class="vote-ranking-name">{{ $vote->user?->name ?? '—' }}</span>
                                <span class="vote-ranking-votes">{{ $vote->votes }}</span>
                            </li>
                        @endforeach
                    </ol>
                @endif

                @auth
                    <p class="vote-panel-note">{{ trans_choice('vote::messages.votes', $userVotes) }}</p>
                @endauth
            </div>
        </div>
    </div>

    @if($goalEnabled)
        <div class="glass-card vote-panel mb-4" id="vote-goal">
            <div class="d-flex justify-content-between align-items-baseline mb-2">
                <h2 class="vote-panel-title mb-0">{{ trans('vote::messages.sections.goal') }}</h2>
                <span class="text-body-secondary" id="goal-text">
                    {{ trans('vote::messages.goal', ['current' => $goalProgress, 'target' => $goalTarget]) }}
                </span>
            </div>

            <div class="progress vote-goal-bar">
                <div class="progress-bar" role="progressbar" style="width: {{ min($goalPercentage, 100) }}%"
                     aria-valuenow="{{ $goalProgress }}" aria-valuemin="0" aria-valuemax="{{ $goalTarget }}"></div>
            </div>
        </div>
    @endif

    @if($displayRewards && ! $rewards->isEmpty())
        <h2 class="vote-panel-title mt-5">{{ trans('vote::messages.sections.rewards') }}</h2>

        <div class="row g-3">
            @foreach($rewards as $reward)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="glass-card vote-reward">
                        @if($reward->image)
                            <img src="{{ $reward->imageUrl() }}" alt="" width="56" height="56">
                        @else
                            <i class="bi bi-gift" aria-hidden="true"></i>
                        @endif
                        <span class="vote-reward-name">{{ $reward->name }}</span>
                        <span class="vote-reward-chances">{{ $reward->chances }} %</span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection

@push('scripts')
    @include('vote::partials._scripts')
@endpush
