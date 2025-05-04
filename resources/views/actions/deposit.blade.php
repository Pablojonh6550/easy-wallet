@extends('layouts.app')

@section('title', 'Easy Wallet | Depositar')

@section('content')
    <section class="w-100 min-vh-100 d-flex justify-content-center align-items-center items-center">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 offset-md-3">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="mb-3">
                                <div class="row ">
                                    <div class="col-9 d-flex flex-column justify-content-center align-items-start">
                                        <span class="text-dark fw-medium fs-5">Olá, <span class="fw-bold">
                                                {{ Auth::user()->name }}👋</span></span>
                                        <span class="text-gray">
                                            Qual valor você deseja depositar?
                                        </span>
                                    </div>
                                    <div class="col-3">
                                        <a class="btn btn-secondary" href="{{ route('dashboard') }}">Voltar</a>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div id="deposit-form">
                                    <div class="mb-3">
                                        <input type="text" class="form-control" placeholder="R$ 0,00" id="amount"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <button type="button" class="btn btn-primary w-100" id="open-deposit-modal">
                                            Depositar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmação -->
        <div class="modal fade" id="confirmDepositModal" tabindex="-1" aria-labelledby="confirmDepositModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="modal-deposit-form" action="{{ route('deposit.form-deposit') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDepositModalLabel">Confirmar Depósito</h5>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="password" class="form-label">Digite sua senha para confirmar:</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <input type="hidden" name="amount" id="modal-amount">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Confirmar Depósito</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('amount');
            const modalAmountInput = document.getElementById('modal-amount');
            const modalForm = document.getElementById('modal-deposit-form');
            const passwordInput = document.getElementById('password');

            // Máscara de valor
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                value = (parseFloat(value) / 100).toFixed(2);
                e.target.value = 'R$ ' + value.replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            });

            // Preencher valor no hidden do modal ao abrir
            const depositBtn = document.getElementById('open-deposit-modal');
            const depositModal = new bootstrap.Modal(document.getElementById('confirmDepositModal'));

            depositBtn.addEventListener('click', function() {
                const amountValue = input.value.trim();

                if (!amountValue || amountValue === 'R$ 0,00') {
                    alert('Por favor, preencha um valor para depositar.');
                    return;
                }

                const rawValue = input.value.replace(/[^\d,]/g, '');
                const floatValue = rawValue.replace(',', '.');
                modalAmountInput.value = parseFloat(floatValue).toFixed(2);

                depositModal.show();
            });


            // Validação do modal antes de enviar
            modalForm.addEventListener('submit', function(e) {
                if (!passwordInput.value.trim()) {
                    e.preventDefault();
                    alert('Por favor, digite sua senha para confirmar o depósito.');
                }
            });
        });
    </script>
@endpush
