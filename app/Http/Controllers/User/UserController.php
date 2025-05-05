<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use App\Http\Requests\User;
use App\http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\Transaction\TransactionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\UserUpdateRequest;

class UserController extends Controller
{
    public function __construct(protected UserService $userService, protected TransactionService $transactionService) {}

    public function index(): View|RedirectResponse
    {
        try {

            $latestTransfers = $this->transactionService->getLastTransactionsByUser(Auth::user());
            return view('dashboard.index', compact('latestTransfers'));
        } catch (\Exception $e) {
            Log::error('Erro ao tentar carregar dashboard', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function show(int $id): View|RedirectResponse
    {
        try {

            $user = $this->userService->find($id);

            return view('users.show', compact('user'));
        } catch (\Exception $e) {
            Log::error('Erro ao tentar carregar usuário', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(int $id): View|RedirectResponse
    {
        try {

            $user = $this->userService->find($id);

            return view('users.edit', compact('user'));
        } catch (\Exception $e) {
            Log::error('Erro ao tentar editar usuário', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function update(UserUpdateRequest $request, int $id): RedirectResponse
    {
        try {
            $this->userService->update($id, $request->validated());

            return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao tentar atualizar usuário', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function delete(int $id): RedirectResponse
    {
        try {
            $this->userService->delete($id);

            return redirect()->route('users.index')->with('success', 'Usuário removido com sucesso.');
        } catch (\Exception $e) {
            Log::error('Erro ao tentar remover usuário', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }
}
