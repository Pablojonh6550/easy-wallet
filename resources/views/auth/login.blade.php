@extends('layouts.app')

@section('title', 'Easy Wallet | Login')

@section('content')
    <section class="w-100 min-vh-100 d-flex justify-content-center align-items-center items-center">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-6 d-flex align-items-center">
                                    <img src="{{ asset('image/card.png') }}" alt="Login" class="img-fluid">
                                </div>
                                <div class="col-12 col-lg-6 d-flex justify-content-center align-items-center">
                                    <form method="POST" class="w-100" action="{{ route('login') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email" autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                required autocomplete="current-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="remember">
                                                    Lembrar me
                                                </label>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary">Login</button>
                                        </div>
                                        <a href="{{ route('register') }}">
                                            <button type="button" class="btn btn-outline-primary">Registrar-se</button>
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
