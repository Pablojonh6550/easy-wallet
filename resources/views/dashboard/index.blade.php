@extends('layouts.app')

@section('title', 'Easy Wallet | Dashboard')

@section('content')
    <section class="w-100 min-vh-100 d-flex justify-content-center align-items-center items-center">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-body p-3">

                            <div class="mb-3 w-100 d-flex justify-content-between align-items-center">
                                <span class="text-dark fw-medium fs-5">Seja bem-vindo, <span class="fw-bold">
                                        {{ Auth::user()->name }}👋</span></span>
                                <a href="{{ route('logout') }}" class="btn btn-danger">Sair</a>
                            </div>
                            <div
                                class="py-2 mb-3 w-100 value-card d-flex flex-column justify-content-between align-items-center">
                                <span class="text-white">Saldo disponível</span>
                                <span class="fw-bold text-white fs-1">R$ 1.000,00</span>
                            </div>

                            <div class="mb-3 w-100 d-flex justify-content-between align-items-center gap-4">
                                <a href="{{ route('deposit') }}" class="btn btn-primary">Depositar</a>
                                <a href="" class="btn btn-primary">Transferir</a>
                                <a href="" class="btn btn-primary">Historico</a>
                            </div>

                            <div>
                                <span>Extrato</span>
                                {{-- lista --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
