<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Interfaces\Auth\AuthInterface;
use Illuminate\View\View;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

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
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $this->authService->login($credentials);

            return redirect()->intended('/');
        } catch (\Exception $error) {

            Log::error('Erro ao tentar login', [
                'email' => $request->validated('email'),
                'exception' => $error->getMessage(),
                'trace' => $error->getTraceAsString(),
            ]);

            return back()->with('error', $error->getMessage());
        }
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        try {

            $this->authService->register($request->validated());

            return redirect('/');
        } catch (\Exception $e) {

            Log::error('Erro ao tentar registrar', [
                'email' => $request->validated('email'),
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }

    public function logout(): RedirectResponse
    {
        try {

            $this->authService->logout();

            return redirect('/');
        } catch (\Exception $e) {

            Log::error('Erro ao tentar logout', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }
}
