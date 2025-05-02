<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use App\Http\Requests\User;
use App\http\Requests\Auth\ChangePasswordRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function show(int $id): View
    {
        $user = $this->userService->find($id);

        return view('users.show', compact('user'));
    }

    public function edit(int $id): View
    {
        $user = $this->userService->find($id);

        return view('users.edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, int $id): RedirectResponse
    {
        $this->userService->update($id, $request->validated());

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function delete(int $id): RedirectResponse
    {
        $this->userService->delete($id);

        return redirect()->route('users.index')->with('success', 'Usuário removido com sucesso.');
    }
}
