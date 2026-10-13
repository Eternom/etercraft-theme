@extends('layouts.base')

@section('app')
    <section class="page-header">
        <div class="container">
            <h1 class="page-title" data-reveal>@yield('title')</h1>
        </div>
    </section>

    <main class="container content pb-5">
        @include('elements.session-alerts')

        @yield('content')
    </main>
@endsection
