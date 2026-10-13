{{--
    Boutons du hero. $server est le serveur par défaut d'Azuriom (Administration → Serveurs) : son adresse se copie
    en un clic ; si une « URL pour rejoindre » y est réglée, un bouton Rejoindre passe en premier, comme sur
    l'accueil d'origine d'Azuriom.
--}}
<div class="hero-actions d-flex flex-column flex-sm-row gap-3 mt-4" data-reveal>
    @if($server)
        @if($server->join_url)
            <a href="{{ $server->join_url }}" class="btn btn-primary btn-lg btn-glow">
                <i class="bi bi-play-fill"></i> {{ trans('messages.server.join') }}
            </a>
        @endif

        <button type="button" class="btn btn-lg copy-ip {{ $server->join_url ? 'btn-glass' : 'btn-primary btn-glow' }}"
                data-copy="{{ $server->fullAddress() }}"
                data-copied="{{ trans('theme::messages.hero.copied') }}"
                title="{{ trans('theme::messages.hero.copy') }}">
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
