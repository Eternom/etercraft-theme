{{--
    Discussion du forum, habillée par le thème (remplace forum::discussions.show).
    Les « j'aime » passent par le script d'Azuriom : [data-like-url], .likes-count, .load-spinner.
--}}
@extends('forum::layouts.forum', ['includeTitle' => false])

@section('title', $discussion->title)

@push('meta')
    <meta property="og:article:author:username" content="{{ $discussion->author->name }}">
    <meta property="og:article:published_time" content="{{ $discussion->created_at->toIso8601String() }}">
    <meta property="og:article:modified_time" content="{{ $discussion->updated_at->toIso8601String() }}">
@endpush

@section('forum')
    @php($canModerate = auth()->user()?->can('forum.discussions'))

    <div class="section-head-split mb-4">
        <div>
            <h1 class="discussion-title">
                @foreach($discussion->tags as $tag)
                    <span class="badge" style="{{ $tag->getBadgeStyle() }}">{{ $tag->name }}</span>
                @endforeach
                {{ $discussion->title }}
            </h1>
            <p class="text-body-secondary mb-0">{{ trans_choice('forum::messages.discussions.views', $discussion->views) }}</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            @can('forum.discussions')
                <form action="{{ route('forum.discussions.'.($discussion->is_pinned ? 'unpin' : 'pin'), $discussion) }}" method="POST">
                    @csrf
                    <button class="btn btn-glass btn-sm @if($discussion->is_pinned) active @endif" title="{{ trans('forum::messages.actions.'.($discussion->is_pinned ? 'unpin' : 'pin')) }}" data-bs-toggle="tooltip">
                        <i class="bi bi-{{ $discussion->is_pinned ? 'pin' : 'pin-angle' }}"></i>
                    </button>
                </form>

                <form action="{{ route('forum.discussions.'.($discussion->is_locked ? 'unlock' : 'lock'), $discussion) }}" method="POST">
                    @csrf
                    <button class="btn btn-glass btn-sm @if($discussion->is_locked) active @endif" title="{{ trans('forum::messages.actions.'.($discussion->is_locked ? 'unlock' : 'lock')) }}" data-bs-toggle="tooltip">
                        <i class="bi bi-{{ $discussion->is_locked ? 'unlock' : 'lock' }}"></i>
                    </button>
                </form>
            @endcan

            @if(! $discussion->is_locked || $canModerate)
                @can('update', $discussion)
                    <a href="{{ route('forum.discussions.edit', $discussion) }}" class="btn btn-glass btn-sm" title="{{ trans('messages.actions.edit') }}" data-bs-toggle="tooltip">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                @endcan

                @can('delete', $discussion)
                    <form action="{{ route('forum.discussions.destroy', $discussion) }}" method="POST" onsubmit="return confirm('{{ trans('forum::messages.discussions.delete') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-glass btn-sm text-danger" title="{{ trans('messages.actions.delete') }}" data-bs-toggle="tooltip">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                @endcan
            @endif
        </div>
    </div>

    @foreach($discussion->posts as $post)
        <article class="glass-card forum-post mb-3">
            <aside class="forum-post-author">
                <img src="{{ $post->author->getAvatar(128) }}" alt="" width="72" height="72">
                <div>
                    <a href="{{ route('forum.users.show', $post->author) }}" class="forum-post-name">{{ $post->author->name }}</a>
                    <span class="badge" style="{{ $post->author->role->getBadgeStyle() }}">
                        @if($post->author->role->icon) <i class="{{ $post->author->role->icon }}"></i> @endif
                        {{ $post->author->role->name }}
                    </span>
                </div>
                <ul class="forum-post-stats">
                    <li><span>{{ trans('forum::messages.profile.discussions') }}</span> {{ $post->author->discussions_count }}</li>
                    <li><span>{{ trans('forum::messages.profile.posts') }}</span> {{ $post->author->posts_count }}</li>
                    <li><span>{{ trans('forum::messages.profile.likes') }}</span> {{ $post->author->likes_count }}</li>
                </ul>
            </aside>

            <div class="forum-post-main">
                <time class="forum-post-date" datetime="{{ $post->created_at->toIso8601String() }}">{{ format_date($post->created_at, true) }}</time>

                <div class="markdown-body">
                    {{ $post->parseContent() }}

                    @if($post->author->signature !== null)
                        <hr>
                        {{ $post->author->parseSignature() }}
                    @endif
                </div>

                @if($loop->first && $discussion->hasPoll())
                    @include('forum::polls._show', ['poll' => $discussion->poll, 'user' => $user])
                @endif

                <footer class="forum-post-actions">
                    <button type="button" class="btn btn-glass btn-sm forum-like @if($post->isLiked()) active @endif" @guest disabled @endguest data-like-url="{{ route('forum.posts.like', $post) }}">
                        <i class="bi bi-hand-thumbs-up"></i>
                        <span class="likes-count">{{ $post->likes->count() }}</span>
                        <span class="d-none spinner-border spinner-border-sm load-spinner" role="status"></span>
                    </button>

                    @if(! $loop->first && (! $discussion->is_locked || $canModerate))
                        @can('edit', $post)
                            <a href="{{ route('forum.discussions.posts.edit', [$discussion, $post]) }}" class="btn btn-glass btn-sm" title="{{ trans('messages.actions.edit') }}" data-bs-toggle="tooltip">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        @endcan

                        @can('delete', $post)
                            <form action="{{ route('forum.discussions.posts.destroy', [$discussion, $post]) }}" method="POST" onsubmit="return confirm('{{ trans('forum::messages.posts.delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-glass btn-sm text-danger" title="{{ trans('messages.actions.delete') }}" data-bs-toggle="tooltip">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endcan
                    @endif
                </footer>
            </div>
        </article>
    @endforeach

    {{ $discussion->posts->links() }}

    @if($discussion->is_locked)
        <p class="text-body-secondary"><i class="bi bi-lock"></i> {{ trans('forum::messages.discussions.locked_info') }}</p>
    @endif

    @if(! $discussion->is_locked || $canModerate)
        @can('create', \Azuriom\Plugin\Forum\Models\Post::class)
            <div class="glass-card forum-reply">
                <h2 class="forum-list-title mb-3">{{ trans('forum::messages.discussions.respond') }}</h2>

                <form action="{{ route('forum.discussions.posts.store', $discussion) }}" method="POST">
                    @csrf

                    <input type="hidden" name="pending_id" value="{{ $pendingId }}">

                    @include('forum::elements.markdown-editor', [
                        'editorMinHeight' => 150,
                        'imagesUploadUrl' => route('forum.posts.attachments.pending', $pendingId),
                    ])

                    <div class="mb-3">
                        <label class="form-label visually-hidden" for="content">{{ trans('messages.fields.content') }}</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4"></textarea>
                        @error('content')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-glow">
                        <i class="bi bi-reply"></i> {{ trans('forum::messages.discussions.respond') }}
                    </button>
                </form>
            </div>
        @endcan
    @endif
@endsection
