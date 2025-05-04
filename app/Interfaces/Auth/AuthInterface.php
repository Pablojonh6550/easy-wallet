<?php

namespace App\Interfaces\Auth;

use Illuminate\Http\Request;
use App\Models\User;

interface AuthInterface
{
    public function login(array $credentials): bool;
    public function register(array $data): User;
    public function logout(): void;
}
