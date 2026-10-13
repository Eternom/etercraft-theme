@if($server)
    <span class="status-pill" data-reveal>
        @if($server->isOnline())
            <span class="status-dot online"></span>
            {{ trans_choice('theme::messages.hero.online', $server->getOnlinePlayers()) }}
        @else
            <span class="status-dot"></span>
            {{ trans('theme::messages.hero.offline') }}
        @endif
    </span>
@endif
