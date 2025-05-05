<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Services\Transaction\TransactionService;
use App\Http\Requests\Deposit\DepositRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Transfer\TransferRequest;
use App\Services\DataBank\DataBankService;
use App\Http\Requests\Reverse\ReverseRequest;
use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function __construct(protected TransactionService $transactionService, protected DataBankService $dataBankService) {}

    public function showDeposit(): View
    {
        return view('actions.deposit');
    }

    public function showTransfer(): View
    {
        return view('actions.transfer');
    }

    public function showHistory(): View|RedirectResponse
    {
        try {

            $transactions = $this->transactionService->getTransactionsByUser(Auth::user());
            return view('history.index', compact('transactions'));
        } catch (Exception $e) {
            Log::error('Erro ao tentar listar transações', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function deposit(DepositRequest $request): RedirectResponse
    {
        try {

            if (Hash::check($request->validated('password'), Auth::user()->password)) {
                $result = $this->transactionService->deposit(Auth::user(), floatval($request->validated('amount')));
                if ($result)
                    return redirect()->route('dashboard')->with('success', 'Depósito realizado com sucesso.');
            }
            return redirect()->back()->with('error', 'Não foi possível realizar o depósito.');
        } catch (Exception $e) {
            Log::error('Erro ao tentar realizar depósito', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function transfer(TransferRequest $request): RedirectResponse
    {
        try {

            $user = Auth::user();

            if (!Hash::check($request->validated('password'), $user->password)) {
                return redirect()->back()->with('error', 'Senha incorreta.');
            }

            $account = $this->dataBankService->findByAccount($request->validated('account'));

            if (!$account) {
                return redirect()->back()->with('error', 'Conta de destino não encontrada.');
            }

            $receiver = $account->user;

            $amount = floatval($request->validated('amount'));

            $result = $this->transactionService->transfer($user, $receiver, $amount);

            if (!$result) {
                return redirect()->back()->with('error', 'Saldo insuficiente ou cheque especial insuficiente.');
            }

            return redirect()->route('dashboard')->with('success', 'Transferência realizada com sucesso.');
        } catch (Exception $e) {
            Log::error('Erro ao tentar realizar transferência', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function reverse(ReverseRequest $request): RedirectResponse
    {
        try {
            if (!Hash::check($request->validated('password'), Auth::user()->password)) {
                return redirect()->route('history.index')->with('error', 'Senha inválida. A reversão não foi realizada.');
            }

            $result = $this->transactionService->reverseTransactionById($request->validated('transaction_id'));

            if ($result) {
                return redirect()->route('history.index')->with('success', 'Reversão realizada com sucesso.');
            }

            return redirect()->route('history.index')->with('error', 'Não foi possível realizar a reversão. Verifique a transação.');
        } catch (InvalidArgumentException $e) {
            Log::error('Erro ao tentar realizar reversão', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('history.index')->with('error', $e->getMessage());
        } catch (Exception $e) {
            Log::error('Erro ao tentar realizar reversão', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('history.index')->with('error', 'Erro inesperado ao tentar reverter a transação.');
        }
    }
}
