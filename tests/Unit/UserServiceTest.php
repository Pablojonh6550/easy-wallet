<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\User\UserService;
use App\Services\DataBank\DataBankService;
use App\Interfaces\User\UserInterface;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    protected $userRepository;
    protected $dataBankService;
    protected UserService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = Mockery::mock(UserInterface::class);
        $this->dataBankService = Mockery::mock(DataBankService::class);

        $this->service = new UserService($this->userRepository, $this->dataBankService);
    }

    public function test_all_returns_collection()
    {
        $collection = new Collection([new User()]);

        $this->userRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($collection);

        $result = $this->service->all();

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
    }

    public function test_find_returns_user_or_null()
    {
        $user = new User(['id' => 1]);

        $this->userRepository
            ->shouldReceive('findById')
            ->with(1)
            ->once()
            ->andReturn($user);

        $result = $this->service->find(1);

        $this->assertInstanceOf(User::class, $result);
    }

    public function test_create_creates_user_and_databank()
    {
        $userData = ['name' => 'Test User', 'email' => 'test@example.com'];
        $user = new User($userData);

        $this->userRepository
            ->shouldReceive('create')
            ->once()
            ->with($userData)
            ->andReturn($user);

        $this->dataBankService
            ->shouldReceive('create')
            ->once()
            ->with($user);

        $result = $this->service->create($userData);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('Test User', $result->name);
    }

    public function test_update_returns_true_on_success()
    {
        $this->userRepository
            ->shouldReceive('update')
            ->once()
            ->with(1, ['name' => 'Updated'])
            ->andReturn(true);

        $result = $this->service->update(1, ['name' => 'Updated']);

        $this->assertTrue($result);
    }

    public function test_delete_returns_true_on_success()
    {
        $this->userRepository
            ->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $result = $this->service->delete(1);

        $this->assertTrue($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
