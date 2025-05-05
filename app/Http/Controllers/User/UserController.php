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

    /**
     * Display the user dashboard.
     *
     * @return View|RedirectResponse
     */
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

    /**
     * Display the specified user's details.
     *
     * @param int $id The ID of the user to display.
     * @return View|RedirectResponse Returns the user details view or redirects back with an error message on failure.
     */
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

    /**
     * Display the form for editing the specified user's details.
     *
     * @param int $id The ID of the user to edit.
     * @return View|RedirectResponse Returns the user edit view or redirects back with an error message on failure.
     */
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

    /**
     * Updates the specified user's details.
     *
     * @param UserUpdateRequest $request The request object containing validated user data.
     * @param int $id The ID of the user to update.
     * @return RedirectResponse Redirects to the users index page with a success message if the update is successful,
     *                          or back with an error message on failure.
     */
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

    /**
     * Removes the specified user from the database.
     *
     * @param int $id The ID of the user to remove.
     * @return RedirectResponse Redirects to the users index page with a success message if the removal is successful,
     *                          or back with an error message on failure.
     */
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
