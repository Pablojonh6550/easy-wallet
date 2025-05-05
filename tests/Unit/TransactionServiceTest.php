<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\DataBank;
use App\Models\Transaction;
use App\Services\Transaction\TransactionService;
use App\Interfaces\Transaction\TransactionInterface;
use App\Services\DataBank\DataBankService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    protected $transactionRepository;
    protected $dataBankService;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transactionRepository = \Mockery::mock(TransactionInterface::class);
        $this->dataBankService = \Mockery::mock(DataBankService::class);
        $this->service = new TransactionService($this->transactionRepository, $this->dataBankService);
    }

    public function test_all()
    {
        $this->transactionRepository->shouldReceive('all')->once()->andReturn(collect());
        $result = $this->service->all();
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    public function test_find()
    {
        $mockTransaction = new Transaction(['id' => 1]);
        $this->transactionRepository->shouldReceive('findById')->with(1)->once()->andReturn($mockTransaction);
        $result = $this->service->find(1);
        $this->assertEquals(1, $result->id);
    }

    public function test_get_transactions_by_user()
    {
        $user = User::factory()->make(['id' => 1]);
        $this->transactionRepository->shouldReceive('getTransactionsByUser')->with(1)->once()->andReturn(collect());
        $result = $this->service->getTransactionsByUser($user);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    public function test_get_last_transactions_by_user()
    {
        $user = User::factory()->make(['id' => 1]);
        $this->transactionRepository->shouldReceive('getLastTransactionsByUser')->with(1)->once()->andReturn(collect());
        $result = $this->service->getLastTransactionsByUser($user);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result);
    }

    public function test_deposit_should_update_balances_and_create_transaction()
    {
        $user = User::factory()->make(['id' => 1]);
        $user->setRelation('dataBank', new DataBank(['id' => 1, 'balance' => 0, 'balance_special' => 50]));

        $amount = 100;
        $mockTransaction = new Transaction(['id' => 1]);

        $this->transactionRepository->shouldReceive('create')->once()->andReturn($mockTransaction);
        $this->dataBankService->shouldReceive('update')->once()->with(1, [
            'balance' => 50,
            'balance_special' => 100,
        ]);

        $transaction = $this->service->deposit($user, $amount);
        $this->assertEquals(1, $transaction->id);
    }

    public function test_transfer_success()
    {
        $sender = User::factory()->make(['id' => 1]);
        $receiver = User::factory()->make(['id' => 2]);

        $sender->setRelation('dataBank', new DataBank(['id' => 1, 'balance' => 100, 'balance_special' => 50]));
        $receiver->setRelation('dataBank', new DataBank(['id' => 2, 'balance' => 20, 'balance_special' => 0]));

        $mockTransaction = new Transaction(['id' => 1]);

        $this->transactionRepository->shouldReceive('create')->once()->andReturn($mockTransaction);
        $this->dataBankService->shouldReceive('update')->twice();

        $result = $this->service->transfer($sender, $receiver, 100);
        $this->assertEquals(1, $result->id);
    }

    public function test_transfer_insufficient_funds()
    {
        $sender = User::factory()->make(['id' => 1]);
        $receiver = User::factory()->make(['id' => 2]);

        $sender->setRelation('dataBank', new DataBank(['id' => 1, 'balance' => 10, 'balance_special' => 10]));
        $receiver->setRelation('dataBank', new DataBank(['id' => 2, 'balance' => 20, 'balance_special' => 0]));

        $result = $this->service->transfer($sender, $receiver, 100);
        $this->assertNull($result);
    }

    public function test_update()
    {
        $this->transactionRepository->shouldReceive('update')->with(1, ['amount' => 200])->once()->andReturn(true);
        $result = $this->service->update(1, ['amount' => 200]);
        $this->assertTrue($result);
    }

    public function test_delete()
    {
        $this->transactionRepository->shouldReceive('delete')->with(1)->once()->andReturn(true);
        $result = $this->service->delete(1);
        $this->assertTrue($result);
    }

    // Exemplo básico de teste para reversão (demais cenários podem ser adicionados)
    public function test_reverse_transaction_should_throw_if_not_found()
    {
        $this->transactionRepository->shouldReceive('findById')->with(1)->once()->andReturn(null);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Transação não encontrada.');
        $this->service->reverseTransactionById(1);
    }
}
