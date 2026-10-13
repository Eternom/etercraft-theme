{{-- Page d'un forum (sous-forums et discussions), habillée par le thème (remplace forum::show) --}}
@extends('forum::layouts.forum', ['withSearch' => true])

@section('title', $forum->name)

@section('forum')
    @unless($forum->forums->isEmpty())
        <div class="glass-card forum-rows mb-4">
            @foreach($forum->forums as $subForum)
                @include('elements.forum-row', ['forum' => $subForum])
            @endforeach
        </div>
    @endunless

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="forum-list-title">{{ trans('forum::messages.discussions.title') }}</h2>

        @can('create', [\Azuriom\Plugin\Forum\Models\Discussion::class, $forum])
            <a href="{{ route('forum.forum.discussions.create', $forum->slug) }}" class="btn btn-primary btn-glow">
                <i class="bi bi-plus-lg"></i> {{ trans('messages.actions.create') }}
            </a>
        @endcan
    </div>

    @if($forum->is_locked)
        <p class="text-body-secondary"><i class="bi bi-lock"></i> {{ trans('forum::messages.forums.locked') }}</p>
    @endif

    <div class="forum-feed mb-4">
        @foreach($forum->discussions as $discussion)
            @php($lastPost = $discussion->posts->first())
            <div class="forum-feed-item">
                <img src="{{ $discussion->author->getAvatar(64) }}" alt="" class="forum-feed-avatar" width="44" height="44">

                <div class="forum-feed-body">
                    <h3>
                        @if($discussion->is_pinned)
                            <i class="bi bi-pin-angle-fill text-primary" title="{{ trans('forum::messages.discussions.pinned') }}"></i>
                        @endif
                        @if($discussion->is_locked)
                            <i class="bi bi-lock-fill text-warning" title="{{ trans('forum::messages.discussions.locked') }}"></i>
                        @endif
                        @foreach($discussion->tags as $tag)
                            <span class="badge" style="{{ $tag->getBadgeStyle() }}">{{ $tag->name }}</span>
                        @endforeach
                        <a href="{{ route('forum.discussions.show', $discussion) }}" class="stretched-link">{{ $discussion->title }}</a>
                    </h3>
                    <span>{{ $discussion->author->name }} · {{ format_date($discussion->created_at, true) }}</span>
                </div>

                @if($lastPost)
                    <span class="forum-feed-last d-none d-md-block">
                        {{ $lastPost->author->name }}<br>
                        <small>{{ format_date($lastPost->created_at) }}</small>
                    </span>
                @endif

                <span class="forum-feed-replies" title="{{ trans_choice('forum::messages.discussions.posts', $discussion->posts_count) }}">
                    <i class="bi bi-chat" aria-hidden="true"></i> {{ $discussion->posts_count }}
                </span>
            </div>
        @endforeach
    </div>

    {{ $forum->discussions->links() }}
@endsection
