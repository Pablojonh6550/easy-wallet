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
                                <span class="text-white">💰 Saldo disponível</span>
                                <span class="fw-bold text-white fs-1">R$
                                    {{ number_format(Auth::user()->dataBank->balance, 2, ',', '.') }}</span>
                                <span class="text-white"> 💵 Cheque especial: R$
                                    {{ number_format(Auth::user()->dataBank->balance_special, 2, ',', '.') }}</span>
                            </div>
                            <div class="mb-3 w-100">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bankDataModal">
                                    Dados bancários
                                </button>
                            </div>
                            <div class="mb-3 w-100 d-flex justify-content-between align-items-center gap-4">
                                <a href="{{ route('deposit.index') }}" class="btn btn-primary">Depositar</a>
                                <a href="{{ route('transfer.index') }}" class="btn btn-primary">Transferir</a>
                                <a href="{{ route('history.index') }}" class="btn btn-primary">Historico</a>
                            </div>

                            <div>
                                <h6 class="mt-4">📃 Últimas transferências</h6>
                                {{-- lista --}}
                                <div class="card p-3 border-0">
                                    @forelse ($latestTransfers as $transaction)
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <div class="d-flex align-items-center">

                                                <div class="me-3 fs-4">
                                                    @if ($transaction->type === 'deposit')
                                                        💰
                                                    @elseif ($transaction->type === 'transfer')
                                                        🔁
                                                    @elseif ($transaction->type === 'transfer' && $transaction->user_id_receiver === Auth::user()->id)
                                                        📥
                                                    @elseif ($transaction->type === 'reversal')
                                                        🔄
                                                    @endif
                                                </div>

                                                <div>
                                                    @if ($transaction->type === 'reversal')
                                                        <div class="fw-semibold">
                                                            {{ ucfirst('Reversão') }}
                                                        </div>
                                                    @else
                                                        <div class="fw-semibold">
                                                            {{ ucfirst($transaction->type === 'transfer' ? 'Transferência' : 'Depósito') }}
                                                        </div>
                                                    @endif
                                                    <small class="text-muted">
                                                        {{ $transaction->created_at->format('d/m/Y') }}
                                                    </small>

                                                </div>
                                            </div>

                                            @php
                                                $received =
                                                    ($transaction->type === 'transfer' &&
                                                        $transaction->user_id_receiver === Auth::user()->id) ||
                                                    $transaction->type === 'deposit'
                                                        ? true
                                                        : false;
                                            @endphp

                                            @if ($transaction->type === 'reversal')
                                                <div class="fw-bold me-3 text-gray">
                                                    R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                                </div>
                                            @else
                                                <div class="fw-bold me-3 {{ $received ? 'text-success' : 'text-danger' }}">
                                                    {{ $received ? '+' : '-' }}
                                                    R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="text-muted text-center w-100">Nenhuma transferência encontrada</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal -->
    <div class="modal fade" id="bankDataModal" tabindex="-1" aria-labelledby="bankDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bankDataModalLabel">Dados Bancários</h5>
                </div>
                <div class="modal-body d-flex flex-column">
                    <!-- Conteúdo dos dados bancários aqui -->
                    <span class="font-weight-bold">Name: <span
                            class="font-weight-normal">{{ Auth::user()->name }}</span></span>
                    <span class="font-weight-bold">Conta: <span
                            class="font-weight-normal">{{ Auth::user()->dataBank->number_account }}</span></span>
                    <span class="font-weight-bold">Email: <span
                            class="font-weight-normal">{{ Auth::user()->email }}</span></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
