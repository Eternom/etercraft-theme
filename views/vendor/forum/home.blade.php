{{-- Accueil du forum, habillé par le thème (remplace forum::home) --}}
@extends('forum::layouts.forum')

@section('title', trans('forum::messages.title'))

@section('forum')
    <div class="row g-4" id="forum">
        <div class="col-lg-9">
            @if($homeMessage !== null)
                <div class="shop-welcome mb-4">{{ $homeMessage }}</div>
            @endif

            @foreach($categories as $category)
                <section class="forum-category">
                    <header class="forum-category-head">
                        <h2>{{ $category->name }}</h2>
                        @if($category->description)
                            <p>{{ $category->description }}</p>
                        @endif
                    </header>

                    <div class="glass-card forum-rows">
                        @foreach($category->forums as $forum)
                            @can('view', $forum)
                                @include('elements.forum-row', ['forum' => $forum])
                            @endcan
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>

        <aside class="col-lg-3">
            @auth
                <a href="{{ route('forum.users.show', $user) }}" class="glass-card forum-me mb-4">
                    <img src="{{ $user->getAvatar(64) }}" alt="" width="48" height="48">
                    <span>
                        <span class="forum-me-name">{{ $user->name }}</span>
                        <span class="badge" style="{{ $user->role->getBadgeStyle() }}">
                            @if($user->role->icon) <i class="{{ $user->role->icon }}"></i> @endif
                            {{ $user->role->name }}
                        </span>
                    </span>
                </a>
            @endauth

            <div class="glass-card shop-side-card mb-4">
                <p class="shop-side-title"><i class="bi bi-graph-up"></i> {{ trans('forum::messages.stats.title') }}</p>
                <ul class="forum-stats">
                    <li>{{ trans_choice('forum::messages.stats.discussions', $discussionsCount) }}</li>
                    <li>{{ trans_choice('forum::messages.stats.posts', $postsCount) }}</li>
                    <li>{{ trans_choice('forum::messages.stats.users', $usersCount) }}</li>
                </ul>
            </div>

            @unless($latestPosts->isEmpty())
                <div class="glass-card shop-side-card mb-4">
                    <p class="shop-side-title"><i class="bi bi-chat-dots"></i> {{ trans('forum::messages.latest.title') }}</p>
                    @foreach($latestPosts as $post)
                        <div class="shop-recent">
                            <img src="{{ $post->author->getAvatar(48) }}" alt="" width="32" height="32">
                            <div class="min-w-0">
                                <a href="{{ route('forum.discussions.show', $post->discussion) }}" class="forum-latest-title">{{ $post->discussion->title }}</a>
                                <small class="text-body-secondary d-block">{{ $post->author->name }} · {{ format_date($post->created_at) }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endunless

            <div class="glass-card shop-side-card mb-4">
                <p class="shop-side-title"><i class="bi bi-people"></i> {{ trans('forum::messages.online.title') }}</p>
                @forelse($onlineUsers as $onlineUser)
                    <a href="{{ route('forum.users.show', $onlineUser) }}" class="forum-online">
                        <img src="{{ $onlineUser->getAvatar(32) }}" alt="" width="20" height="20"> {{ $onlineUser->name }}
                    </a>
                @empty
                    <span class="text-body-secondary small">{{ trans('forum::messages.online.none') }}</span>
                @endforelse
            </div>
        </aside>
    </div>
@endsection
