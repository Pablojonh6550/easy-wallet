<?php

namespace Tests\Unit;

use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use Illuminate\Support\Facades\Session;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $userService;
    protected $authService;

    protected function setUp(): void
    {
        parent::setUp();

        Session::shouldReceive('regenerate')->andReturn(true);

        $this->userService = Mockery::mock(UserService::class);
        $this->authService = new AuthService($this->userService);
    }

    public function test_login_with_valid_credentials()
    {
        $request = Mockery::mock(LoginRequest::class);
        $request->shouldReceive('validated')->with('email')->andReturn('test@example.com');
        $request->shouldReceive('validated')->with('password')->andReturn('password');

        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'test@example.com', 'password' => 'password'])
            ->andReturn(true);

        session()->shouldReceive('regenerate')->once();

        $result = $this->authService->login($request->validated());

        $this->assertTrue($result);
    }

    public function test_login_with_invalid_credentials_throws_exception()
    {

        $request = Mockery::mock(LoginRequest::class);
        $request->shouldReceive('validated')->with('email')->andReturn('wrong@example.com');
        $request->shouldReceive('validated')->with('password')->andReturn('wrongpass');

        Auth::shouldReceive('attempt')
            ->once()
            ->with(['email' => 'wrong@example.com', 'password' => 'wrongpass'])
            ->andReturn(false);

        $this->expectException(ValidationException::class);

        $this->authService->login($request->validated());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
