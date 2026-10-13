<form class="wiki-search" action="{{ route('wiki.search') }}" method="GET" role="search">
    <i class="bi bi-search" aria-hidden="true"></i>
    <input type="search" name="q" value="{{ $search ?? '' }}" placeholder="{{ trans('theme::messages.wiki.search') }}" aria-label="{{ trans('messages.actions.search') }}" required>
</form>
