@if(! $posts->isEmpty())
    <section id="news" class="section">
        <div class="container">
            <div class="section-head text-center" data-reveal>
                <h2>{{ trans('theme::messages.news.title') }}</h2>
            </div>

            <div class="row g-4">
                @foreach($posts as $post)
                    <div class="col-md-6 col-lg-4" data-reveal>
                        <a href="{{ route('posts.show', $post) }}" class="glass-card post-card h-100">
                            @if($post->hasImage())
                                <div class="post-image">
                                    <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}">
                                </div>
                            @endif
                            <div class="p-4">
                                <p class="post-date">{{ format_date($post->published_at) }}</p>
                                <h3>{{ $post->title }}</h3>
                                <p class="text-body-secondary mb-0">{{ Str::limit(strip_tags($post->content), 140) }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
