<?php

namespace App\Http\Controllers\Action\Deposit;

use App\Http\Controllers\Controller;
use App\Services\Deposit\DepositService;
use Illuminate\Contracts\View\View;

class DepositController extends Controller
{
    // public function __construct(protected DepositService $depositService) {}

    public function showDeposit(): View
    {
        return view('actions.deposit');
    }

    public function deposit(DepositRequest $request)
    {
        return 1;
    }
}
