@extends('layouts.app')

@section('title', 'Easy Wallet | Register')

@section('content')
    <section class="w-100 min-vh-100 d-flex justify-content-center align-items-center items-center">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route(name: 'form-register') }}" method="POST" class="w-100 p-4">
                                @csrf
                                <div class="mb-3 w-100">
                                    <h2 class="mb-3">Registrar-se para continuar</h2>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome</label>
                                    <input id="name" type="name"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
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
                                    <label for="password_confirmation" class="form-label">Confirmar Password</label>
                                    <input id="password_confirmation" type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" required autocomplete="current-password">
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Registrar</button>
                                </div>
                                <hr>
                                <div class="mb-3 text-center">
                                    Já possui cadastro? <a href="{{ route('login') }}">Clique aqui!</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
