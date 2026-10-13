{{-- Ligne d'un forum ($forum, avec discussions_count et posts_count chargés par le plugin) --}}
<a href="{{ route('forum.show', $forum->slug) }}" class="forum-row">
    <span class="forum-row-icon"><i class="{{ $forum->icon ?? 'bi bi-chat' }}" aria-hidden="true"></i></span>
    <span class="forum-row-body">
        <span class="forum-row-name">{{ $forum->name }}</span>
        @if($forum->description)
            <span class="forum-row-text">{{ $forum->description }}</span>
        @endif
    </span>
    <span class="forum-row-stats">
        <span>{{ trans_choice('forum::messages.forums.discussions', $forum->discussions_count) }}</span>
        <span>{{ trans_choice('forum::messages.discussions.posts', $forum->posts_count) }}</span>
    </span>
</a>
