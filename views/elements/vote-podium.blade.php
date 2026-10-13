{{-- Podium des trois meilleurs voteurs du mois ($voters : résultat de Vote::getTopVoters, indexé par position) --}}
@if($voters->isEmpty())
    <p class="podium-empty">{{ trans('theme::messages.vote.empty') }}</p>
@else
    <ol class="podium">
        {{-- Ordre visuel 2 - 1 - 3 : le premier au centre, sur la marche la plus haute --}}
        @foreach([2, 1, 3] as $position)
            @if($voter = $voters->get($position))
                <li class="podium-place podium-place-{{ $position }}">
                    @if($voter->user)
                        <img src="{{ $voter->user->getAvatar(96) }}" alt="" class="podium-avatar" width="64" height="64">
                    @endif
                    <span class="podium-name">{{ $voter->user?->name ?? '—' }}</span>
                    <span class="podium-votes">{{ trans_choice('theme::messages.vote.votes', $voter->votes) }}</span>
                    <span class="podium-step">{{ $position }}</span>
                </li>
            @endif
        @endforeach
    </ol>
@endif
