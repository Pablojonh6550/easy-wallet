<?php

namespace App\Services\Auth;

use App\Services\User\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Interfaces\Auth\AuthInterface;
use Illuminate\Support\Facades\Session;

class AuthService implements AuthInterface
{
    public function __construct(protected UserService $userService) {}

    /**
     * Attempt to log in a user with the provided credentials.
     *
     * @param array $credentials The array containing 'email' and 'password' for authentication.
     * @return bool True if login is successful, otherwise throws a ValidationException.
     * @throws ValidationException If the provided credentials are invalid.
     */
    public function login(array $credentials): bool
    {
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => 'Credenciais inválidas.',
            ]);
        }

        session()->regenerate();
        return true;
    }

    public function register(array $data): User
    {

        $user = $this->userService->create($data);

        Auth::login($user);

        return $user;
    }

    public function logout(): void
    {
        Auth::logout();

        Session::invalidate();
        Session::regenerateToken();
    }
}
