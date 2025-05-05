<?php

namespace Tests\Unit;

use App\Models\DataBank;
use App\Models\User;
use App\Services\DataBank\DataBankService;
use App\Interfaces\DataBank\DataBankInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class DataBankServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $dataBankRepository;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dataBankRepository = Mockery::mock(DataBankInterface::class);
        $this->service = new DataBankService($this->dataBankRepository);
    }

    public function test_all_returns_collection()
    {
        $expected = DataBank::factory()->count(3)->make();

        $this->dataBankRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn($expected);

        $result = $this->service->all();

        $this->assertEquals($expected, $result);
    }

    public function test_find_returns_databank_by_id()
    {
        $dataBank = DataBank::factory()->make(['id' => 1]);

        $this->dataBankRepository
            ->shouldReceive('findById')
            ->with(1)
            ->once()
            ->andReturn($dataBank);

        $result = $this->service->find(1);

        $this->assertEquals($dataBank, $result);
    }

    public function test_find_by_account_returns_databank()
    {
        $dataBank = DataBank::factory()->make(['number_account' => 1234]);

        $this->dataBankRepository
            ->shouldReceive('findByAccount')
            ->with(1234)
            ->once()
            ->andReturn($dataBank);

        $result = $this->service->findByAccount(1234);

        $this->assertEquals($dataBank, $result);
    }

    public function test_create_creates_new_databank()
    {
        $user = User::factory()->make(['id' => 5]);
        $data = DataBank::factory()->make(['user_id' => 5])->toArray();
        $expected = new DataBank($data);

        $this->dataBankRepository
            ->shouldReceive('create')
            ->with(Mockery::on(fn($arg) => $arg['user_id'] === 5))
            ->once()
            ->andReturn($expected);

        $result = $this->service->create($user);

        $this->assertEquals($expected->user_id, $result->user_id);
    }

    public function test_update_returns_true_on_success()
    {
        $this->dataBankRepository
            ->shouldReceive('update')
            ->with(1, ['balance' => 500])
            ->once()
            ->andReturn(true);

        $result = $this->service->update(1, ['balance' => 500]);

        $this->assertTrue($result);
    }

    public function test_delete_returns_true_on_success()
    {
        $this->dataBankRepository
            ->shouldReceive('delete')
            ->with(1)
            ->once()
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
