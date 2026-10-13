<div class="hero-actions d-flex flex-column flex-sm-row gap-3 mt-4" data-reveal>
    @if($server)
        <button type="button" class="btn btn-primary btn-lg btn-glow copy-ip"
                data-copy="{{ $server->fullAddress() }}"
                data-copied="{{ trans('theme::messages.hero.copied') }}">
            <i class="bi bi-copy"></i>
            <span class="copy-label">{{ $server->fullAddress() }}</span>
        </button>
    @endif
    @if(Route::has('vote.home'))
        <a href="{{ route('vote.home') }}" class="btn btn-glass btn-lg">
            <i class="bi bi-hand-thumbs-up"></i> {{ trans('theme::messages.hero.vote') }}
        </a>
    @endif
    @if($discord)
        <a href="{{ $discord->value }}" target="_blank" rel="noopener noreferrer" class="btn btn-glass btn-lg">
            <i class="bi bi-discord"></i> Discord
        </a>
    @endif
</div>
