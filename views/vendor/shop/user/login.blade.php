{{-- Achat sans compte : saisie du pseudo, habillée par le thème (remplace shop::user.login) --}}
@extends('layouts.app')

@section('title', trans('auth.login'))

@section('content')
    <h1>{{ trans('auth.login') }}</h1>

    <div class="row g-4" id="shop">
        <div class="col-lg-3">
            @include('shop::categories._sidebar')
        </div>

        <div class="col-lg-9">
            <div class="glass-card shop-form">
                <form method="POST" action="{{ route('shop.login') }}">
                    @csrf

                    <label class="form-label" for="name">{{ trans('auth.name') }}</label>
                    <div class="input-group @error('name') has-validation @enderror">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i> {{ trans('auth.login') }}
                        </button>
                        @error('name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
