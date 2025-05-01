<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Interfaces\Auth\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function __construct(protected AuthInterface $authService) {}

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $this->authService->login($credentials);

        return redirect()->intended('/');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {

        $this->authService->register($request->validated());

        return redirect('/');
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout($request);

        return redirect('/');
    }
}
