{{-- Fil d'Ariane du forum, habillé par le thème (remplace forum::elements.nav) --}}
<nav aria-label="breadcrumb" class="forum-breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ trans('messages.home') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('forum.home') }}">{{ trans('forum::messages.title') }}</a></li>

        @foreach(($current ?? null)?->getNavigationStack() ?? [] as $breadcrumbLink => $breadcrumbName)
            <li class="breadcrumb-item"><a href="{{ $breadcrumbLink }}">{{ $breadcrumbName }}</a></li>
        @endforeach
    </ol>
</nav>
