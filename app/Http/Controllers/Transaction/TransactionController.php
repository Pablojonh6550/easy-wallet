<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Services\Transaction\TransactionService;
use App\Http\Requests\Deposit\DepositRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(protected TransactionService $transactionService) {}

    public function showDeposit(): View
    {
        return view('actions.deposit');
    }

    public function deposit(DepositRequest $request): RedirectResponse
    {
        if (Hash::check($request->validated('password'), Auth::user()->password)) {
            $result = $this->transactionService->deposit(Auth::user(), floatval($request->validated('amount')));
            if ($result)
                return redirect()->route('dashboard')->with('success', 'Depósito realizado com sucesso.');
        }
        return redirect()->back()->with('error', 'Não foi possível realizar o depósito.');
    }
}
