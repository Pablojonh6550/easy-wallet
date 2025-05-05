@extends('layouts.app')

@section('title', 'Easy Wallet | Dashboard')

@section('content')
    <section class="w-100 min-vh-100 d-flex justify-content-center align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-9 d-flex flex-column justify-content-center align-items-start">
                                    <span class="text-dark fw-medium fs-5">Olá, <span class="fw-bold">
                                            {{ Auth::user()->name }}👋</span></span>
                                </div>
                                <div class="col-3">
                                    <a class="btn btn-secondary" href="{{ route('dashboard') }}">Voltar</a>
                                </div>
                            </div>

                            <div>
                                <h5 class="mt-4">📃 Extrato</h5>
                                {{-- lista --}}
                                @foreach ($transactions as $transaction)
                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 fs-4">
                                                @if ($transaction->type === 'deposit')
                                                    💰
                                                @elseif ($transaction->type === 'transfer' && $transaction->user_id_receiver === Auth::user()->id)
                                                    📥
                                                @elseif ($transaction->type === 'transfer')
                                                    🔁
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
                                                $transaction->type === 'deposit';
                                        @endphp

                                        <div class="d-flex align-items-center">
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

                                            @if (in_array($transaction->type, ['deposit', 'transfer']) && $transaction->type !== 'reversal')
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                    data-bs-target="#revertModal"
                                                    data-transaction-id="{{ $transaction->id }}">
                                                    Reverter
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal para confirmação -->
    <div class="modal fade" id="revertModal" tabindex="-1" aria-labelledby="revertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('transactions.reverse') }}" id="revertForm">
                @csrf
                <input type="hidden" name="transaction_id" id="transaction_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="revertModalLabel">Confirmar Reversão</h5>

                    </div>
                    <div class="modal-body">
                        <p>Por favor, insira sua senha para confirmar a reversão:</p>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Senha" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Confirmar Reversão</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const revertModal = document.getElementById('revertModal');
        revertModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const transactionId = button.getAttribute('data-transaction-id');

            const inputId = revertModal.querySelector('#transaction_id');
            inputId.value = transactionId;
        });
    </script>
@endpush
