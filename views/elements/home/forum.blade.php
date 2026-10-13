{{-- Section Forum (plugin Forum) : discussions récentes des forums publics, comme le flux RSS du plugin --}}
@if(plugins()->isEnabled('forum') && config('theme.show_forum', true))
    @php
        $discussions = \Azuriom\Plugin\Forum\Models\Discussion::query()
            ->whereHas('forum', fn ($query) => $query->whereNull('roles'))
            ->scopes('publiclyVisible')
            ->with(['author', 'forum'])
            ->withCount('posts')
            ->latest('updated_at')
            ->take((int) config('theme.forum_count', 5))
            ->get();
    @endphp

    @unless($discussions->isEmpty())
        <section id="forum" class="section">
            <div class="container">
                <div class="section-head-split" data-reveal>
                    <div>
                        <span class="eyebrow">{{ site_name() }}</span>
                        <h2>{{ config('theme.forum_title') ?: trans('theme::messages.forum.title') }}</h2>
                        <p>{{ trans('theme::messages.forum.subtitle') }}</p>
                    </div>
                    <a href="{{ route('forum.home') }}" class="btn btn-glass">
                        {{ trans('theme::messages.forum.see_all') }} <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="forum-feed">
                    @foreach($discussions as $discussion)
                        <a href="{{ route('forum.discussions.show', $discussion) }}" class="forum-feed-item" data-reveal>
                            @if($discussion->author)
                                <img src="{{ $discussion->author->getAvatar(64) }}" alt="" class="forum-feed-avatar" width="44" height="44">
                            @endif
                            <div class="forum-feed-body">
                                <h3>{{ $discussion->title }}</h3>
                                <span>{{ $discussion->forum->name }} · {{ $discussion->author?->name }} · {{ $discussion->updated_at->diffForHumans() }}</span>
                            </div>
                            {{-- Le premier message est le sujet lui-même : il ne compte pas comme réponse --}}
                            <span class="forum-feed-replies" title="{{ trans_choice('theme::messages.forum.replies', max($discussion->posts_count - 1, 0)) }}">
                                <i class="bi bi-chat" aria-hidden="true"></i> {{ max($discussion->posts_count - 1, 0) }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endunless
@endif
