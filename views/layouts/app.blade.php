@extends('layouts.base')

@section('app')
    <main class="container content page-content pb-5">
        @include('elements.session-alerts')

        @yield('content')
    </main>
@endsection
